<?php

namespace Omnipay\CapitaPay360;

use Omnipay\Common\Item as BaseItem;

/**
 * Capita Pay360 sale line item
 */
class Item extends BaseItem
{
    /**
     * Get the item reference (acccount code)
     */
    public function getReference()
    {
        return $this->getParameter('reference');
    }

    /**
     * Set the item reference (acccount code)
     */
    public function setReference($value)
    {
        return $this->setParameter('reference', substr($value, 0, 50));
    }

    /**
     * Get the item additional reference
     */
    public function getAdditionalReference()
    {
        return $this->getParameter('additionalReference');
    }

    /**
     * Set the item additional reference
     */
    public function setAdditionalReference($value)
    {
        return $this->setParameter('additionalReference', substr($value, 0, 50));
    }

    /**
     * Get the item fund code
     */
    public function getFundCode()
    {
        return $this->getParameter('fundCode');
    }

    /**
     * Set the item fund code
     */
    public function setFundCode($value)
    {
        return $this->setParameter('fundCode', substr($value, 0, 5));
    }

    /**
     * Get the item narrative
     */
    public function getNarrative()
    {
        return $this->getParameter('narrative');
    }

    /**
     * Set the item narrative
     */
    public function setNarrative($value)
    {
        return $this->setParameter('narrative', substr($value, 0, 50));
    }
}
