<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/*
 * This file is part of the Larium Shop package.
 *
 * (c) Andreas Kollaros <andreaskollaros@ymail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Larium\Shop\Payment;

use Finite\StatefulInterface;
use Finite\Event\TransitionEvent;
use Larium\Shop\Sale\AdjustableInterface;
use Larium\Shop\Sale\OrderInterface;
use Larium\Shop\Common\Collection;
use Larium\Shop\Payment\Provider\RedirectResponse;
use Larium\Shop\Sale\Adjustment;
use Money\Money;
use InvalidArgumentException;

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

    /**
     * Amount to be paid on this payment
     *
     * @var Money\Money
     * @access protected
     */
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
        return $this->payment_amount();
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
    public function process($state = null)
    {
        if (null === $this->getPaymentMethod()) {
            throw new InvalidArgumentException("You must set a PaymentMethod for this Payment.");
        }

        $amount = $this->payment_amount();

        $response = $this->invoke_provider($amount, $state);

        $this->create_transaction_from_response($response);

        if ($response instanceOf RedirectResponse) {
            $this->setState('pending');
        }

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

    protected function payment_amount()
    {
        if (null === $this->amount && null === $this->getOrder()) {
            throw new \InvalidArgumentException('Amount cannot br null');
        }

        return $this->amount ?: $this->getOrder()->getTotalAmount();
    }

    /**
     * Creates an adjustment for payment cost if needed and calculates the
     * amount for this payment.
     *
     * If Payment has already an amount then this amount will be used else
     * the total amount of Order will be used.
     *
     * @access protected
     * @return void
     */
    protected function create_payment_method_adjustment()
    {
        if (null === $this->getPaymentMethod()) {

            return;
        }

        $cost = $this->getPaymentMethod()->getCost();

        if ($cost->getAmount()) {
            $adj = new Adjustment();
            $adj->setAmount($cost);
            $adj->setLabel($this->getIdentifier());
            $this->getOrder()->addAdjustment($adj);

            $this->getOrder()->calculateTotalAmount();
        }
    }

    /**
     * Resolves provider from payment method and executes given state.
     *
     * State can be resolved either from given argument or from payment method.
     * @throw InvalidArgumentException If payment provider is not an instance
     *                                 of PaymentProviderInterface.
     * @param Money\Money $amount
     * @param string $state
     * @access protected
     * @return Provider\Response
     */
    protected function invoke_provider($amount, $state = null)
    {
        $action = $state ?: $this->getPaymentMethod()->getAction();
        $provider = $this->getPaymentMethod()->getProvider();

        if ($provider instanceof PaymentProviderInterface) {
            // Invoke the method of Provider based on given action.
            $providerMethod = new \ReflectionMethod($provider, $action);
            // Arguments to pass to PaymentProvider.
            $params = array($amount, $this->options());

            return $providerMethod->invokeArgs($provider, $params);
        } else {
            throw new InvalidArgumentException('Provider must implements Larium\Shop\Payment\PaymentProviderInterface');
        }
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
     * Gets the payment response after a transaction.
     *
     * @access public
     * @return Response
     */
    public function getResponse()
    {
        return $this->response;
    }
}
