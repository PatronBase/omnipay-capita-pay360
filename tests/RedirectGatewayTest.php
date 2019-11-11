<?php

namespace Omnipay\CapitaPay360;

use Omnipay\Tests\GatewayTestCase;

class RedirectGatewayTest extends GatewayTestCase
{
    /** @var array */
    protected $options;

    public function setUp()
    {
        parent::setUp();

        $this->gateway = new RedirectGateway($this->getHttpClient(), $this->getHttpRequest());

        $this->options = array(
            'scpId' => '999008881',
            'siteId' => 'My Store',
            'hmacKeyId' => '871',
            'hmacKey' => 'Mk9m98IfEblmPfrpsawt7BmxObt98Jev',
            'currency' => 'EUR',
            'testMode' => true,
        );
    }

    public function testSetup()
    {
        foreach ($this->options as $key => $value) {
            $this->gateway->{'set'.ucfirst($key)}($value);
        }

        $this->assertTrue($this->gateway->supportsPurchase());
        $this->assertTrue($this->gateway->supportsCompletePurchase());

        $this->assertSame('999008881', $this->gateway->getScpId());
        $this->assertSame('My Store', $this->gateway->getSiteId());
        $this->assertSame('871', $this->gateway->getHmacKeyId());
        $this->assertSame('Mk9m98IfEblmPfrpsawt7BmxObt98Jev', $this->gateway->getHmacKey());
        $this->assertSame('EUR', $this->gateway->getCurrency());
        $this->assertTrue($this->gateway->getTestMode());
    }
}
