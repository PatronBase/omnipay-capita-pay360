<?php

namespace Omnipay\CapitaPay360\Message;

use Omnipay\Tests\TestCase;

class CompletePurchaseResponseTest extends TestCase
{
    /** @var CompletePurchaseResponse */
    private $response;

    public function testCompletePurchaseSuccess()
    {
        $responseData = (object) array(
            'requestId' => '123456789',
            'scpReference' => 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789',
            'transactionState' => 'COMPLETE',
            'storeCardResult' => (object) array(
                'status' => 'NOT_ATTEMPTED'
            ),
            'paymentResult' => (object) array(
                'status' => 'SUCCESS',
                'paymentDetails' => (object) array(
                    'paymentHeader' => (object) array(
                        'transactionDate' => '2019-11-26T08:34:10Z',
                        'machineCode' => '00089',
                        'uniqueTranId' => 'ABCDEFGHIJKL',
                    ),
                    'authDetails' => (object) array(
                        'authCode' => '123456',
                        'amountInMinorUnits' => '145',
                        'maskedCardNumber' => '484421******5643',
                        'cardDescription' => 'VISA',
                        'cardType' => 'DEBIT',
                        'merchantNumber' => '12345678',
                        'expiryDate' => '1122',
                        'continuousAuditNumber' => '101',
                    ),
                    'saleSummary' => (object) array(
                        'items' => (object) array(
                            'itemSummary' => array(
                                (object) array(
                                    'lineId' => '1',
                                    'continuousAuditNumber' => '100',
                                )
                            )
                        )
                    )
                )
            )
        );
        $this->response = new CompletePurchaseResponse($this->getMockRequest(), $responseData);

        $this->assertTrue($this->response->isSuccessful());
        $this->assertSame('123456', $this->response->getTransactionReference());
        $this->assertNull($this->response->getMessage());
        $this->assertSame('VISA', $this->response->getCardBrand());
        $this->assertSame('1122', $this->response->getCardExpiry());
        $this->assertSame('484421******5643', $this->response->getCardNumber());
    }

    public function testCompletePurchaseFailure()
    {
        $responseData = (object) array(
            'requestId' => '123456789',
            'scpReference' => 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789',
            'transactionState' => 'COMPLETE',
            'storeCardResult' => (object) array(
                'status' => 'NOT_ATTEMPTED',
                'errorDetails' => (object) array(
                    'errorId' => 'abcdefghijklmnopqrstuvwxyz',
                    'errorMessage' => 'Pay only',
                )
            ),
            'paymentResult' => (object) array(
                'status' => 'CARD_DETAILS_REJECTED',
                'errorDetails' => (object) array(
                    'errorId' => 'zyxwvutsrqponmlkjihgfedcba',
                    'errorMessage' => 'Card rejected',
                )
            ),
        );
        $this->response = new CompletePurchaseResponse($this->getMockRequest(), $responseData);

        $this->assertFalse($this->response->isSuccessful());
        $this->assertNull($this->response->getTransactionReference());
        $this->assertSame('Card rejected', $this->response->getMessage());
        $this->assertNull($this->response->getCardBrand());
        $this->assertNull($this->response->getCardExpiry());
        $this->assertNull($this->response->getCardNumber());
    }

    public function testCompletePurchaseError()
    {
        $responseData = (object) array(
            'requestId' => '123456789',
            'scpReference' => '',
            'transactionState' => 'INVALID_REFERENCE',
        );
        $this->response = new CompletePurchaseResponse($this->getMockRequest(), $responseData);

        $this->assertFalse($this->response->isSuccessful());
        $this->assertNull($this->response->getTransactionReference());
        $this->assertSame('INVALID_REFERENCE', $this->response->getMessage());
        $this->assertNull($this->response->getCardBrand());
        $this->assertNull($this->response->getCardExpiry());
        $this->assertNull($this->response->getCardNumber());
    }
}
