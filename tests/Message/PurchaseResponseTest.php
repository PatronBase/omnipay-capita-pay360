<?php

namespace Omnipay\CapitaPay360\Message;

use Omnipay\Tests\TestCase;

class PurchaseResponseTest extends TestCase
{
    /** @var PurchaseResponse */
    private $response;

    public function testPurchaseSuccess()
    {
        $this->response = new PurchaseResponse($this->getMockRequest(), (object) array(
            'requestId' => '9876ZYX',
            'scpReference' => 'Y2xldmVyU2NwUmVmZXJlbmNl',
            'transactionState' => 'IN_PROGRESS',
            'invokeResult' => (object) array(
                'status' => 'SUCCESS',
                'redirectUrl' => 'https://sbsctest.e-paycapita.com:443/scp/scpcli?ssk=Y2xldmVyU2NwUmVmZXJlbmNlU1NL',
            )
        ));

        $this->assertFalse($this->response->isSuccessful());
        $this->assertTrue($this->response->isRedirect());
        $this->assertSame('Y2xldmVyU2NwUmVmZXJlbmNl', $this->response->getTransactionReference());
        $this->assertNull($this->response->getMessage());
        $this->assertSame(
            'https://sbsctest.e-paycapita.com:443/scp/scpcli?ssk=Y2xldmVyU2NwUmVmZXJlbmNlU1NL',
            $this->response->getRedirectUrl()
        );
        $this->assertSame('GET', $this->response->getRedirectMethod());
        $this->assertNull($this->response->getRedirectData());
    }

    public function testPurchaseError()
    {
        $this->response = new PurchaseResponse($this->getMockRequest(), (object) array(
            'scpReference' => 'Y2xldmVyU2NwUmVmZXJlbmNl',
            'transactionState' => 'COMPLETE',
            'invokeResult' => (object) array(
                'status' => 'INVALID_REQUEST',
                'errorDetails' => (object) array(
                    'errorId' => 'Y2xldmVyU2NwUmVmZXJlbmNlU1NL',
                    'errorMessage' => 'Sale amount in sale summary does not match total of item amounts',
                )
            )
        ));

        $this->assertFalse($this->response->isSuccessful());
        $this->assertFalse($this->response->isRedirect());
        $this->assertSame('Y2xldmVyU2NwUmVmZXJlbmNl', $this->response->getTransactionReference());
        $this->assertSame(
            'Sale amount in sale summary does not match total of item amounts',
            $this->response->getMessage()
        );
        $this->assertNull($this->response->getRedirectUrl());
        $this->assertSame('GET', $this->response->getRedirectMethod());
        $this->assertNull($this->response->getRedirectData());
    }
}
