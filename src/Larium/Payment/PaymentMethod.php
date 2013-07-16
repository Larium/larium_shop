<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Payment;

class PaymentMethod
{
    protected $id;

    protected $title;

    protected $description;

    protected $cost;

    protected $source_class;

    protected $provider_class;

    protected $provider;

    protected $payment_source;

    protected $source_options;

    public function initialize(array $options=array())
    {
        $default = array(
            'source_class' => '',
            'provider_class' => '',
            'title' => '',
            'cost' => 0,
            'id' => null,
            'description' => ''
        );

        $options = array_merge($default, $options);

        $this->source_class = $options['source_class'];
        $this->provider_class = $options['provider_class'];
        $this->title = $options['title'];
        $this->cost = $options['cost'];
        $this->id = $options['id'];
        $this->description = $options['description'];
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * Get title.
     *
     * @return string.
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set title.
     *
     * @param string $title the value to set.
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * Get description.
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set description.
     *
     * @param string $description the value to set.
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * Get cost.
     *
     * @return number
     */
    public function getCost()
    {
        return $this->cost;
    }

    /**
     * Set cost.
     *
     * @param number $cost the value to set.
     */
    public function setCost($cost)
    {
        $this->cost = $cost;
    }

    public function getSourceClass()
    {
        return $this->source_class;
    }

    public function setSourceClass($source_class)
    {
        $this->source_class = $source_class;
    }

    public function getProviderClass()
    {
        return $this->provider_class;
    }

    public function setProviderClass($provider_class)
    {
        $this->provider_class = $provider_class;
    }

    /**
     * Gets the PaymentSource for this Payment.
     *
     * Payment sources are objects that satisfy the Payment amount.
     * Can be Creditcards, Giftcards, Cash, Checks etc.
     *
     * @access public
     * @return PaymentSourceInterface
     */
    public function getPaymentSource()
    {
        if (null === $this->payment_source) {
            $source_class = $this->getSourceClass();

            $this->payment_source = new $source_class();

            $this->payment_source->setOptions($this->getSourceOptions());
        }

        return $this->payment_source;
    }

    public function getProvider()
    {
        if (null === $this->provider) {
            $provider_class = $this->provider_class;

            $this->provider = new $provider_class;
        }

        return $this->provider;
    }

    public function getSourceOptions()
    {
        return $this->source_options;
    }

    public function setSourceOptions(array $options=array())
    {
        $this->source_options = $options;
    }
}
