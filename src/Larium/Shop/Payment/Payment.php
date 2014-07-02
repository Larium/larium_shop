<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Shop\Payment;

use Larium\Shop\Sale\AdjustableInterface;
use Larium\Shop\Sale\OrderInterface;
use Finite\StatefulInterface;
use Finite\Event\StateMachineEvent;
use Finite\StateMachine\StateMachine;
use Larium\Shop\StateMachine\StateMachineAwareInterface;
use Larium\Shop\StateMachine\StateMachineAwareTrait;
use Larium\Shop\StateMachine\Transition;
use Larium\Shop\Common\Collection;

/**
 * Payment class implements PaymentInterface and allows the payoff of an Order.
 *
 * It acts as Stateful object and uses StateMachine to control its different
 * states.
 *
 * Available states of a Payment are:
 * - unpaid (initial)
 * - authorized
 * - paid
 * - refunded (final)
 *
 * Available transitions from states are:
 * - purchase
 * - authorize
 * - capture
 * - void
 * - credit
 *
 * @uses PaymentInterface
 * @uses StatefulInterface
 * @author  Andreas Kollaros <andreaskollaros@ymail.com>
 * @license MIT {@link http://opensource.org/licenses/mit-license.php}
 */
class Payment implements PaymentInterface, StatefulInterface
{
    protected $transactions;

    protected $amount;

    protected $tag;

    protected $order;

    protected $payment_method;

    protected $state = 'unpaid';

    protected $identifier;

    protected $response;

    public function __construct()
    {
        $this->initialize();
    }

    public function initialize()
    {
        $this->transactions = new Collection();

        $this->generate_identifier();
    }

    /**
     * {@inheritdoc}
     */
    public function getIdentifier()
    {
        return $this->identifier;
    }

    /**
     * {@inheritdoc}
     */
    public function getTransactions()
    {
        return $this->transactions;
    }

    /**
     * {@inheritdoc}
     */
    public function addTransaction(TransactionInterface $transaction)
    {
        $this->getTransactions()->add($transaction);
    }

    /**
     * {@inheritdoc}
     */
    public function removeTransaction(TransactionInterface $transaction)
    {
        return $this->getTransactions()->remove($transaction, function($trx) use ($transaction){
           return $trx->getTransactionId() == $transaction->getTransactionId();
        });
    }

    /**
     * {@inheritdoc}
     */
    public function containsTransaction(TransactionInterface $transaction)
    {
        return $this->getTransactions()->contains($transaction, function($trx) use ($transaction){
           return $trx->getTransactionId() == $transaction->getTransactionId();
        });
    }

    /**
     * {@inheritdoc}
     */
    public function getTotalTransactionsAmount()
    {
        $amount = 0;

        foreach ($this->getTransactions() as $trx) {
            $amount += $trx->getAmount();
        }

        return $amount;
    }

    /**
     * {@inheritdoc}
     */
    public function getPaymentMethod()
    {
        return $this->payment_method;
    }

    /**
     * {@inheritdoc}
     */
    public function setPaymentMethod(PaymentMethod $payment_method)
    {
        $this->payment_method = $payment_method;
    }

    /**
     * {@inheritdoc}
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
    }

    /**
     * {@inheritdoc}
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * {@inheritdoc}
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * {@inheritdoc}
     */
    public function setOrder(OrderInterface $order)
    {
        $this->order = $order;
        $this->create_payment_method_adjustment();
    }

    /**
     * {@inheritdoc}
     */
    public function detachOrder()
    {
        $label = $this->getIdentifier();

        foreach ($this->order->getAdjustments() as $key => $a) {
            if ($label == $a->getLabel()) {
                $this->order->getAdjustments()->remove($a);
                $this->order = null;

                return true;
                break;
            }
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * {@inheritdoc}
     */
    public function setState($state)
    {
        $this->state = $state;
    }

    /* -(  StateMachine  ) ------------------------------------------------- */

    public function getFiniteState()
    {
        return $this->getState();
    }

    public function setFiniteState($state)
    {
        $this->setState($state);
    }

    /**
     * Tries to set the payment state as paid.
     *
     * @param StateMachine $sm
     * @param Transition $transition
     *
     * @throws InvalidArgumentException If Order or PaymentMethod objects are
     *                                  missing.
     * @access public
     * @return false|Larium\Shop\Payment\Provider\Response
     */
    public function toPaid(StateMachine $sm, Transition $transition)
    {
        if (null === $this->getOrder()) {
            throw new \InvalidArgumentException("You must add this Payment to an Order.");
        }

        if (null === $this->getPaymentMethod()) {
            throw new \InvalidArgumentException("You must set a PaymentMethod for this Payment.");
        }

        // The amount to charge for this payment.
        $amount = $this->payment_amount();

        $response = $this->invoke_provider($amount, $transition->getName());

        $this->create_transaction_from_response($response);

        if ($response->isSuccess()) {
            if (null === $this->amount) {
                $this->setAmount($amount);
            }

            $this->response = $response;

            return $response;
        }

        return false;

    }

    /**
     * Creates an adjustment for payment cost if needed and calculate the
     * amount for this payment.
     *
     * If Payment has received an amount then this amount will be used alse
     * will use the TotalAmount from Order.
     *
     * @access protected
     * @return void
     */
    protected function payment_amount()
    {
        return null === $this->amount
            ? $this->getOrder()->getTotalAmount()
            : $this->amount;
    }

    protected function create_payment_method_adjustment()
    {

        if (null === $this->getPaymentMethod()) {

            return;
        }

        if ($cost = $this->getPaymentMethod()->getCost()) {
            $adj = new \Larium\Shop\Sale\Adjustment();
            $adj->setAmount($cost);
            $adj->setLabel($this->getIdentifier());
            $this->getOrder()->addAdjustment($adj);

            $this->getOrder()->calculateTotalAmount();
        }
    }

    protected function invoke_provider($amount, $action)
    {
        $provider = $this->getPaymentMethod()->getProvider();

        if ($provider instanceof PaymentProviderInterface) {

            // Invoke the method of Provider based on Transition name.
            $providerMethod = new \ReflectionMethod($provider, $action);
            $params = array($amount, $this->options());
        } else {

            throw new Exception('Provider must implements Larium\Shop\Payment\PaymentProviderInterface');
        }

        return $providerMethod->invokeArgs($provider, $params);
    }

    /**
     * Additional options to pass to provider like billing / shipping address,
     * customer info, order number etc.
     *
     * @access protected
     * @return array
     */
    protected function options()
    {
        $options = array();

        return $options;
    }

    protected function create_transaction_from_response(Provider\Response $response)
    {
        $transaction = new Transaction();

        $transaction->setPayment($this);
        $transaction->setAmount($this->payment_amount());
        $transaction->setTransactionId($response->getTransactionId());

        $this->transactions->add($transaction);
    }

    /**
     * Generates a unique identifier for this payment.
     *
     * @access protected
     * @return string
     */
    protected function generate_identifier()
    {
        $this->identifier = uniqid();
    }

    /**
     * Gets response.
     *
     * @access public
     * @return Response
     */
    public function getResponse()
    {
        return $this->response;
    }
}
