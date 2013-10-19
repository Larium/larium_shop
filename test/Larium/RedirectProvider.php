<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Shop\Payment\Provider;

use Larium\Shop\Payment\PaymentProviderInterface;
use Larium\Shop\Payment\PaymentSourceInterface;
use AktiveMerchant\Billing\CreditCard;

class RedirectProvider extends GatewayProvider implements PaymentProviderInterface
{
    public function purchase($amount, array $options=array())
    {
        $response = new RedirectResponse();

        $cc = new CreditCard($this->payment_source->getOptions());

        $r = $this->getGateway()->purchase($amount, $cc, $options);

        $response->setSuccess($r->success());
        $response->setTransactionId($r->authorization());

        return $response;
    }
}
