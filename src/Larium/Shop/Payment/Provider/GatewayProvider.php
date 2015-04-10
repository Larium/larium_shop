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
namespace Larium\Shop\Payment\Provider;

use Larium\Shop\Payment\PaymentProviderInterface;
use Larium\Shop\Payment\PaymentSourceInterface;
use AktiveMerchant\Billing\CreditCard;

class GatewayProvider implements PaymentProviderInterface
{
    protected $gateway_class;

    protected $gateway_options = array();

    protected $gateway;

    protected $payment_source;

    public function purchase($amount, array $options = array())
    {
        $response = new Response();

        $cc = new CreditCard($this->payment_source->getOptions());

        $r = $this->getGateway()->purchase($amount, $cc, $options);

        $response->setSuccess($r->success());
        $response->setTransactionId($r->authorization());

        return $response;
    }

    public function authorize($amount, array $options = array())
    {

    }

    public function capture($amount, $authorization, array $options = array())
    {

    }

    /**
     * Sets the gateway class name to use for transaction.
     *
     * @param string $gateway_class
     * @access public
     * @return void
     */
    public function setGatewayClass($gateway_class)
    {
        $this->gateway_class = $gateway_class;
    }

    public function getGateway()
    {
        if (null === $this->gateway) {
            $class = $this->gateway_class;

            if (null === $class) {
                throw new \InvalidArgumentException("Gateway class not defined.");
            }

            $this->gateway = new $class($this->gateway_options);
        }

        return $this->gateway;
    }

    /**
     * Sets gateway options.
     * Options could be login information for gateway, currency etc, depends on
     * each gataway class.
     *
     * @param array $gateway_options
     * @access public
     * @return void
     */
    public function setGatewayOptions(array $gateway_options = array())
    {
        $this->gateway_options = $gateway_options;
    }

    public function setPaymentSource(PaymentSourceInterface $payment_source)
    {
        $this->payment_source = $payment_source;
    }
}
