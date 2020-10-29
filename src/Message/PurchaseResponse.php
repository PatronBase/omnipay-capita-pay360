<?php

namespace Omnipay\CapitaPay360\Message;

use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RedirectResponseInterface;

/**
 * CapitaPay360 Purchase Response
 */
class PurchaseResponse extends AbstractResponse implements RedirectResponseInterface
{
    public function isSuccessful()
    {
        return false;
    }

    public function isRedirect()
    {
        return $this->data->invokeResult->status == 'SUCCESS';
    }

    public function getTransactionReference()
    {
        return $this->data->scpReference;
    }

    public function getMessage()
    {
        return $this->isRedirect() ? null : $this->data->invokeResult->errorDetails->errorMessage;
    }

    public function getRedirectUrl()
    {
        return $this->isRedirect() ? $this->data->invokeResult->redirectUrl : null;
    }

    public function getRedirectMethod()
    {
        return 'GET';
    }

    public function getRedirectData()
    {
        return null;
    }
}
