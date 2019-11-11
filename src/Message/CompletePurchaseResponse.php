<?php

namespace Omnipay\CapitaPay360\Message;

use Omnipay\Common\Message\AbstractResponse;

/**
 * CapitaPay360 Complete Purchase Response
 */
class CompletePurchaseResponse extends AbstractResponse
{
    /**
     * Is the response successful?
     *
     * @return boolean
     */
    public function isSuccessful()
    {
        return $this->data->paymentResult->status == 'SUCCESS';
    }

    /**
     * Get the authorisation code if available.
     *
     * @return null|string
     */
    public function getTransactionReference()
    {
        return $this->isSuccessful() ? $this->data->paymentResult->paymentDetails->authDetails->authCode : null;
    }

    /**
     * Get the merchant response message if available.
     *
     * @return null|string
     */
    public function getMessage()
    {
        return $this->isSuccessful() ? null : $this->data->paymentResult->errorDetails->errorMessage;
    }

    /**
     * Get the card brand if available e.g. Visa
     *
     * @return null|string
     */
    public function getCardBrand()
    {
        return $this->isSuccessful() ? $this->data->paymentResult->paymentDetails->authDetails->cardDescription : null;
    }

    /**
     * Get the card expiry if available e.g. Visa
     *
     * @return null|string
     */
    public function getCardExpiry()
    {
        return $this->isSuccessful() ? $this->data->paymentResult->paymentDetails->authDetails->expiryDate : null;
    }

    /**
     * Get the card masked number if available e.g. Visa
     *
     * @return null|string
     */
    public function getCardNumber()
    {
        return $this->isSuccessful() ? $this->data->paymentResult->paymentDetails->authDetails->maskedCardNumber : null;
    }
}
