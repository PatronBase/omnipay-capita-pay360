<?php

namespace Omnipay\CapitaPay360\Message;

use Omnipay\Common\CreditCard;
use Omnipay\Tests\TestCase;
use Omnipay\CapitaPay360\Message\PurchaseRequest;
use Omnipay\CapitaPay360\Message\PurchaseResponse;

include_once('overrides.php');

class PurchaseRequestTest extends TestCase
{
    /** @var PurchaseRequest */
    private $request;

    /** @var mixed[]  Data to initialize the request with */
    private $options;

    public function setUp()
    {
        $this->request = new PurchaseRequest($this->getHttpClient(), $this->getHttpRequest());

        $this->options = array(
            'scpId'         => '123456789',
            'hmacKey'       => 'AnAmazinglySecretHash',
            'hmacKeyId'     => '123',
            'amount'        => 99.95,
            'currency'      => 'GBP',
            'transactionId' => '9876ZYX',
            'returnUrl'     => 'http://www.example.com/return',
            'cancelUrl'     => 'http://www.example.com/cancel',
            'siteId'        => 'ABC123',
            'description'   => 'Example product description',
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

        $this->assertSame('payOnly', $data['requestType']);
        $this->assertSame('9876ZYX', $data['requestId']);

        $this->assertSame('http://www.example.com/return', $data['routing']['returnUrl']);
        $this->assertSame('http://www.example.com/cancel', $data['routing']['backUrl']);
        $this->assertSame('ABC123', $data['routing']['siteId']);
        $this->assertSame('123456789', $data['routing']['scpId']);

        $this->assertSame('ECOM', $data['panEntryMethod']);

        $this->assertSame('Example product description', $data['sale']['saleSummary']['description']);
        $this->assertSame(9995, $data['sale']['saleSummary']['amountInMinorUnits']);
    }

    public function testGetDataWithItems()
    {
        // override some data
        $this->options = array_merge(
            $this->options,
            array(
                'items' => array(
                    array(
                        'name' => 'Donation',
                        'description' => 'Fundraiser',
                        'quantity' => '1',
                        'price' => '99.50',
                        'reference' => '1200/BHL00///BAR///',
                        'additionalReference' => '9876ZYX',
                        'fundCode' => '12345',
                        'narrative' => 'Smith donation to Fundraiser on sale 9876ZYX'
                    ),
                    array(
                        'name' => 'Fees',
                        'description' => 'Processing fees',
                        'quantity' => '3',
                        'price' => '0.15',
                        'reference' => '1200/BHL00///BAR///',
                        'additionalReference' => '9876ZYX',
                        'fundCode' => '67890',
                        'narrative' => 'Smith processing fee on sale 9876ZYX'
                    ),
                )
            )
        );
        $this->request->initialize($this->options);
        $data = $this->request->getData();

        $this->assertSame('Donation', $data['sale']['items'][0]['itemSummary']['description']);
        $this->assertSame(9950, $data['sale']['items'][0]['itemSummary']['amountInMinorUnits']);
        $this->assertSame('1200/BHL00///BAR///', $data['sale']['items'][0]['itemSummary']['reference']);
        $this->assertSame('1', $data['sale']['items'][0]['quantity']);
        $this->assertSame('9876ZYX', $data['sale']['items'][0]['lgItemDetails']['additionalReference']);
        $this->assertSame('12345', $data['sale']['items'][0]['lgItemDetails']['fundCode']);
        $this->assertSame(
            'Smith donation to Fundraiser on sale 9876ZYX',
            $data['sale']['items'][0]['lgItemDetails']['narrative']
        );
        $this->assertSame(1, $data['sale']['items'][0]['lineId']);

        $this->assertSame('Fees', $data['sale']['items'][1]['itemSummary']['description']);
        $this->assertSame(45, $data['sale']['items'][1]['itemSummary']['amountInMinorUnits']);
        $this->assertSame('1200/BHL00///BAR///', $data['sale']['items'][1]['itemSummary']['reference']);
        $this->assertSame('3', $data['sale']['items'][1]['quantity']);
        $this->assertSame('9876ZYX', $data['sale']['items'][1]['lgItemDetails']['additionalReference']);
        $this->assertSame('67890', $data['sale']['items'][1]['lgItemDetails']['fundCode']);
        $this->assertSame(
            'Smith processing fee on sale 9876ZYX',
            $data['sale']['items'][1]['lgItemDetails']['narrative']
        );
        $this->assertSame(2, $data['sale']['items'][1]['lineId']);
    }

    public function testGetDataWithCard()
    {
        // override some data
        $this->options = array_merge(
            $this->options,
            array(
                'card' => array(
                    'name' => 'Joanne Smith',
                    'address1' => '123 Nowhere Street',
                    'address2' => 'Special Subdivision',
                    'address3' => 'Suburbia',
                    'city' => 'Tinytown',
                    'country' => 'United Kingdom',
                    'postcode' => 'NR99 1AN',
                )
            )
        );
        $this->request->initialize($this->options);
        $data = $this->request->getData();

        $this->assertTrue($this->request->getCard() instanceof CreditCard);
        $this->assertSame('Joanne Smith', $data['billing']['cardHolderDetails']['cardHolderName']);
        $this->assertSame('123 Nowhere Street', $data['billing']['cardHolderDetails']['address']['address1']);
        $this->assertSame('Special Subdivision', $data['billing']['cardHolderDetails']['address']['address2']);
        $this->assertSame('Suburbia', $data['billing']['cardHolderDetails']['address']['address3']);
        $this->assertSame('Tinytown', $data['billing']['cardHolderDetails']['address']['address4']);
        $this->assertSame('United Kingdom', $data['billing']['cardHolderDetails']['address']['country']);
        $this->assertSame('NR99 1AN', $data['billing']['cardHolderDetails']['address']['postcode']);
    }

    public function testGetDataWithCardAndItem()
    {
        // override some data
        $this->options = array_merge(
            $this->options,
            array(
                'card' => array(
                    'name' => 'Joanne Smith',
                    'email' => 'joanne.smith@example.com',
                    'address1' => '123 Nowhere Street',
                    'address2' => 'Special Subdivision',
                    'address3' => 'Suburbia',
                    'city' => 'Tinytown',
                    'country' => 'United Kingdom',
                    'postcode' => 'NR99 1AN',
                ),
                'items' => array(
                    array(
                        'name' => 'Donation',
                        'description' => 'Fundraiser',
                        'quantity' => '1',
                        'price' => '99.50',
                        'reference' => '1200/BHL00///BAR///',
                        'additionalReference' => '9876ZYX',
                        'fundCode' => '12345',
                        'narrative' => 'Smith donation to Fundraiser on sale 9876ZYX'
                    ),
                    array(
                        'name' => 'Fees',
                        'description' => 'Processing fees',
                        'quantity' => '3',
                        'price' => '0.15',
                        'reference' => '1200/BHL00///BAR///',
                        'additionalReference' => '9876ZYX',
                        'fundCode' => '67890',
                        'narrative' => 'Smith processing fee on sale 9876ZYX'
                    ),
                )
            )
        );
        $this->request->initialize($this->options);
        $data = $this->request->getData();

        $this->assertSame('Joanne', $data['sale']['items'][0]['lgItemDetails']['accountName']['forename']);
        $this->assertSame('Smith', $data['sale']['items'][0]['lgItemDetails']['accountName']['surname']);
        $this->assertSame('123 Nowhere Street', $data['sale']['items'][0]['lgItemDetails']['accountAddress']['address1']);
        $this->assertSame('Special Subdivision', $data['sale']['items'][0]['lgItemDetails']['accountAddress']['address2']);
        $this->assertSame('Suburbia', $data['sale']['items'][0]['lgItemDetails']['accountAddress']['address3']);
        $this->assertSame('Tinytown', $data['sale']['items'][0]['lgItemDetails']['accountAddress']['address4']);
        $this->assertSame('United Kingdom', $data['sale']['items'][0]['lgItemDetails']['accountAddress']['country']);
        $this->assertSame('NR99 1AN', $data['sale']['items'][0]['lgItemDetails']['accountAddress']['postcode']);
        $this->assertSame('joanne.smith@example.com', $data['sale']['items'][0]['lgItemDetails']['contact']['email']);
    }

    public function testSend()
    {
        $mockService = $this->getMockFromWsdl(__DIR__.'/../Mock/capita-pay360-test.wsdl', 'scpSimpleInvoke');
        $mockData = (object) array(
            'requestId' => '9876ZYX',
            'scpReference' => 'Y2xldmVyU2NwUmVmZXJlbmNl',
            'transactionState' => 'IN_PROGRESS',
            'invokeResult' => (object) array(
                'status' => 'SUCCESS',
                'redirectUrl' => 'https://sbsctest.e-paycapita.com:443/scp/scpcli?ssk=Y2xldmVyU2NwUmVmZXJlbmNlU1NL',
            )
        );
        $mockService->expects($this->any())
            ->method('scpSimpleInvoke')
            ->willReturn($mockData);
        $this->request->soapClient = $mockService;

        $response = $this->request->send();

        $this->assertTrue($response instanceof PurchaseResponse);
        $this->assertSame($this->request, $response->getRequest());
        $this->assertSame($mockData, $response->getData());
    }

    public function testGetEndpoint()
    {
        $this->request->setTestMode(true);
        $this->assertSame(
            'https://sbsctest.e-paycapita.com/scp/scpws/scpSimpleClient.wsdl',
            $this->request->getEndpoint()
        );
        $this->request->setTestMode(false);
        $this->assertSame(
            'https://sbs.e-paycapita.com/scp/scpws/scpSimpleClient.wsdl',
            $this->request->getEndpoint()
        );
    }
}
