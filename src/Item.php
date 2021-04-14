<?php

namespace Omnipay\CapitaPay360;

use Omnipay\Common\Item as BaseItem;

/**
 * Capita Pay360 sale line item
 */
class Item extends BaseItem
{
    const CUSTOMER_INFO_KEYS = array(
        'customerString1',
        'customerString2',
        'customerString3',
        'customerString4',
        'customerString5',
        'customerNumber1',
        'customerNumber2',
        'customerNumber3',
        'customerNumber4',
    );

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

    /**
     * Get the customer-specific string 1
     */
    public function getCustomerString1()
    {
        return $this->getParameter('customerString1');
    }

    /**
     * Set the customer-specific string 1
     */
    public function setCustomerString1($value)
    {
        $this->setParameter('customerString1', substr($value, 0, 50));
    }

    /**
     * Get the customer-specific string 2
     */
    public function getCustomerString2()
    {
        return $this->getParameter('customerString2');
    }

    /**
     * Set the customer-specific string 2
     */
    public function setCustomerString2($value)
    {
        $this->setParameter('customerString2', substr($value, 0, 50));
    }

    /**
     * Get the customer-specific string 3
     */
    public function getCustomerString3()
    {
        return $this->getParameter('customerString3');
    }

    /**
     * Set the customer-specific string 3
     */
    public function setCustomerString3($value)
    {
        $this->setParameter('customerString3', substr($value, 0, 50));
    }

    /**
     * Get the customer-specific string 4
     */
    public function getCustomerString4()
    {
        return $this->getParameter('customerString4');
    }

    /**
     * Set the customer-specific string 4
     */
    public function setCustomerString4($value)
    {
        $this->setParameter('customerString4', substr($value, 0, 50));
    }

    /**
     * Get the customer-specific string 5
     */
    public function getCustomerString5()
    {
        return $this->getParameter('customerString5');
    }

    /**
     * Set the customer-specific string 5
     */
    public function setCustomerString5($value)
    {
        $this->setParameter('customerString5', substr($value, 0, 50));
    }

    /**
     * Get the customer-specific integer 1
     */
    public function getCustomerNumber1()
    {
        return $this->getParameter('customerNumber1');
    }

    /**
     * Set the customer-specific integer 1
     */
    public function setCustomerNumber1($value)
    {
        $this->setParameter('customerNumber1', $value === null ? null : (int) $value);
    }

    /**
     * Get the customer-specific integer 2
     */
    public function getCustomerNumber2()
    {
        return $this->getParameter('customerNumber2');
    }

    /**
     * Set the customer-specific integer 2
     */
    public function setCustomerNumber2($value)
    {
        $this->setParameter('customerNumber2', $value === null ? null : (int) $value);
    }

    /**
     * Get the customer-specific integer 3
     */
    public function getCustomerNumber3()
    {
        return $this->getParameter('customerNumber3');
    }

    /**
     * Set the customer-specific integer 3
     */
    public function setCustomerNumber3($value)
    {
        $this->setParameter('customerNumber3', $value === null ? null : (int) $value);
    }

    /**
     * Get the customer-specific integer 4
     */
    public function getCustomerNumber4()
    {
        return $this->getParameter('customerNumber4');
    }

    /**
     * Set the customer-specific integer 4
     */
    public function setCustomerNumber4($value)
    {
        $this->setParameter('customerNumber4', $value === null ? null : (int) $value);
    }

    /**
     * Get all the customer-specific fields (that have values)
     */
    public function getCustomerInfo()
    {
        $info = array();
        foreach (self::CUSTOMER_INFO_KEYS as $key) {
            $info[$key] = $this->{'get'.ucfirst($key)}();
        }
        return array_filter(
            $info,
            function ($x) {
                return $x !== null;
            }
        );
    }

    /**
     * Set all the customer-specific fields
     */
    public function setCustomerInfo($value)
    {
        if (!is_array($value)) {
            return;
        }
        foreach (self::CUSTOMER_INFO_KEYS as $key) {
            if (array_key_exists($key, $value)) {
                $this->{'set'.ucfirst($key)}($value[$key]);
            }
        }
    }
}
