<?php

namespace Omnipay\CapitaPay360\Message;

use Omnipay\Tests\TestCase;
use Omnipay\CapitaPay360\Message\CompletePurchaseRequest;
use Omnipay\CapitaPay360\Message\CompletePurchaseResponse;

include_once('overrides.php');

class CompletePurchaseRequestTest extends TestCase
{
    /** @var CompletePurchaseRequest */
    private $request;

    /** @var mixed[]  Data to initialize the request with */
    private $options;

    public function setUp()
    {
        $this->request = new CompletePurchaseRequest($this->getHttpClient(), $this->getHttpRequest());

        $this->options = array(
            'scpId' => '123456789',
            'hmacKey' => 'AnAmazinglySecretHash',
            'hmacKeyId' => '123',
            'siteId' => 'ABC123',
            'transactionReference' => 'abcdefghijklmno',
        );
        $this->request->initialize($this->options);
    }

    public function testGetData()
    {
        $data = $this->request->getData();

        $this->assertSame('CapitaPortal', $data['credentials']['subject']['subjectType']);
        $this->assertSame('123456789', $data['credentials']['subject']['identifier']);
        $this->assertSame('SCP', $data['credentials']['subject']['systemCode']);

        $this->assertSame('PB123456789', $data['credentials']['requestIdentification']['uniqueReference']);
        $this->assertSame('1574054510', $data['credentials']['requestIdentification']['timeStamp']);

        $this->assertSame('Original', $data['credentials']['signature']['algorithm']);
        $this->assertSame('123', $data['credentials']['signature']['hmacKeyID']);
        $this->assertSame('gAte60FusiEV/I6amg/xX1ARtAw5cOUf3M1FI5Zosg4=', $data['credentials']['signature']['digest']);

        $this->assertSame('ABC123', $data['siteId']);
        $this->assertSame('abcdefghijklmno', $data['scpReference']);
    }

    public function testSend()
    {
        $mockService = $this->getMockFromWsdl(__DIR__.'/../Mock/capita-pay360-test.wsdl', 'scpSimpleQuery');
        $mockData = (object) array(
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
        $mockService->expects($this->any())
            ->method('scpSimpleQuery')
            ->willReturn($mockData);
        $this->request->soapClient = $mockService;

        $response = $this->request->send();

        $this->assertTrue($response instanceof CompletePurchaseResponse);
        $this->assertSame($this->request, $response->getRequest());
        $this->assertSame($mockData, $response->getData());
    }
}
