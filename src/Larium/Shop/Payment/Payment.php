<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/*
 * This file is part of the Larium Shop package.
 *
 * (c) Andreas Kollaros <andreas@larium.net>
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
 * @author Andreas Kollaros <andreas@larium.net>
 */
class Payment implements PaymentInterface, StatefulInterface
{
    protected $transactions;

    /**
     * Amount to be paid.
     *
     * @var Money\Money
     */
    protected $amount;

    protected $tag;

    protected $order;

    protected $payment_method;

    protected $state = self::UNPAID;

    protected $identifier;

    protected $response;

    /**
     * @param OrderInterface $order
     * @param PaymentMethodInterface $paymentMethod
     * @param Money $amount
     */
    public function __construct(
        OrderInterface $order,
        PaymentMethodInterface $paymentMethod,
        $amount = null
    ) {

        $this->generateIdentifier();
        $this->order = $order;
        $this->amount = $amount;
        $this->payment_method = $paymentMethod;
        $order->setPayment($this);

        $this->transactions = new Collection();
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
        return $this->getTransactions()->remove($transaction, function ($trx) use ($transaction) {
            return $trx->getTransactionId() == $transaction->getTransactionId();
        });
    }

    /**
     * {@inheritdoc}
     */
    public function containsTransaction(TransactionInterface $transaction)
    {
        return $this->getTransactions()->contains($transaction, function ($trx) use ($transaction) {
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
    public function getAmount()
    {
        return $this->paymentAmount();
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
    public function detachOrder()
    {
        $label = $this->getIdentifier();

        foreach ($this->order->getAdjustments() as $key => $a) {
            if ($label == $a->getLabel()) {
                $this->order->getAdjustments()->remove($a);
                $this->order = null;

                return true;
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

        $amount = $this->paymentAmount();

        $response = $this->invokeProvider($amount, $state);

        $this->createTransactionFromResponse($response);

        if ($response instanceof RedirectResponse) {
            $this->setState('pending');
        }

        if ($response->isSuccess()) {
            if (null === $this->amount) {
                $this->amount = $amount;
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

    protected function paymentAmount()
    {
        if (null === $this->amount && null === $this->getOrder()) {
            throw new \InvalidArgumentException('Amount cannot be null');
        }

        return $this->amount ?: $this->getOrder()->getTotalAmount();
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
    protected function invokeProvider($amount, $state = null)
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

    protected function createTransactionFromResponse(Provider\Response $response)
    {
        $transaction = new Transaction();

        $transaction->setPayment($this);
        $transaction->setAmount($this->paymentAmount());
        $transaction->setTransactionId($response->getTransactionId());

        $this->transactions->add($transaction);
    }

    /**
     * Generates a unique identifier for this payment.
     *
     * @access protected
     * @return string
     */
    protected function generateIdentifier()
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
