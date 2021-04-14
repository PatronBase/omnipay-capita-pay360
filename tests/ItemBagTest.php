<?php

namespace Omnipay\CapitaPay360;

use Omnipay\Common\Item as BaseItem;
use Omnipay\Tests\TestCase;
use Omnipay\CapitaPay360\Item;
use Omnipay\CapitaPay360\ItemBag;

class ItemBagTest extends TestCase
{
    /** @var ItemBag */
    private $bag;

    /** @var mixed[]  Core data to initialize the test item with (excluding customer info) */
    private $base_options;

    /** @var mixed[]  Data to initialize the test item with */
    private $options;

    public function setUp()
    {
        $this->bag = new ItemBag;
        $this->base_options = array(
            'name' => 'Donation',
            'description' => 'Fundraiser',
            'quantity' => 1,
            'price' => 1.45,
            'reference' => '1400/BHL00///BOOK///',
            'additionalReference' => 'ABCDEF',
            'fundCode' => '12345',
            'narrative' => 'Stay a while, and listen',
        );
        $this->options = $this->base_options + array(
            'customerString1' => 'Once',
            'customerString2' => 'upon',
            'customerString3' => 'a time...',
            'customerString4' => 'there was a string that was so long that it had to be truncated!',
            'customerString5' => '',
            'customerNumber1' => '10',
            'customerNumber2' => '29',
            'customerNumber3' => '38',
            'customerNumber4' => '',
        );
    }

    public function testAddItem()
    {
        $item = new Item($this->options);
        $this->bag->replace(array($item));
        $items = $this->bag->all();

        $this->assertTrue($items[0] instanceof Item);
        $this->assertSame('Donation', $items[0]->getName());
        $this->assertSame('Fundraiser', $items[0]->getDescription());
        $this->assertSame(1, $items[0]->getQuantity());
        $this->assertSame(1.45, $items[0]->getPrice());
        $this->assertSame('1400/BHL00///BOOK///', $items[0]->getReference());
        $this->assertSame('ABCDEF', $items[0]->getAdditionalReference());
        $this->assertSame('12345', $items[0]->getFundCode());
        $this->assertSame('Stay a while, and listen', $items[0]->getNarrative());
        $this->assertSame('Once', $items[0]->getCustomerString1());
        $this->assertSame('upon', $items[0]->getCustomerString2());
        $this->assertSame('a time...', $items[0]->getCustomerString3());
        $this->assertSame('there was a string that was so long that it had to', $items[0]->getCustomerString4());
        $this->assertSame('', $items[0]->getCustomerString5());
        $this->assertSame(10, $items[0]->getCustomerNumber1());
        $this->assertSame(29, $items[0]->getCustomerNumber2());
        $this->assertSame(38, $items[0]->getCustomerNumber3());
        $this->assertSame(0, $items[0]->getCustomerNumber4());
        $this->assertSame(
            array(
                'customerString1' => 'Once',
                'customerString2' => 'upon',
                'customerString3' => 'a time...',
                'customerString4' => 'there was a string that was so long that it had to',
                'customerString5' => '',
                'customerNumber1' => 10,
                'customerNumber2' => 29,
                'customerNumber3' => 38,
                'customerNumber4' => 0,
            ),
            $items[0]->getCustomerInfo()
        );
    }

    public function testAddItemWithCustomerInfoArray()
    {
        $item = new Item($this->base_options + array(
            'customerInfo' => array(
                'customerString1' => 'Once',
                'customerString2' => 'upon',
                'customerString3' => 'a time...',
                'customerString4' => 'there was a string that was so long that it had to be truncated!',
                'customerString5' => '',
                'customerNumber1' => '10',
                'customerNumber2' => '29',
                'customerNumber3' => '38',
                'customerNumber4' => '',
            ),
        ));
        $this->bag->replace(array($item));
        $items = $this->bag->all();

        $this->assertTrue($items[0] instanceof Item);
        $this->assertSame('Once', $items[0]->getCustomerString1());
        $this->assertSame('upon', $items[0]->getCustomerString2());
        $this->assertSame('a time...', $items[0]->getCustomerString3());
        $this->assertSame('there was a string that was so long that it had to', $items[0]->getCustomerString4());
        $this->assertSame('', $items[0]->getCustomerString5());
        $this->assertSame(10, $items[0]->getCustomerNumber1());
        $this->assertSame(29, $items[0]->getCustomerNumber2());
        $this->assertSame(38, $items[0]->getCustomerNumber3());
        $this->assertSame(0, $items[0]->getCustomerNumber4());
    }

    public function testAddItemWithCustomerInfoObject()
    {
        $item = new Item($this->base_options + array(
            'customerInfo' => (object) array(
                'customerString1' => 'Once',
                'customerString2' => 'upon',
                'customerString3' => 'a time...',
                'customerString4' => 'there was a string that was so long that it had to be truncated!',
                'customerString5' => '',
                'customerNumber1' => '10',
                'customerNumber2' => '29',
                'customerNumber3' => '38',
                'customerNumber4' => '',
            ),
        ));
        $this->bag->replace(array($item));
        $items = $this->bag->all();

        $this->assertTrue($items[0] instanceof Item);
        $this->assertNull($items[0]->getCustomerString1());
        $this->assertNull($items[0]->getCustomerString2());
        $this->assertNull($items[0]->getCustomerString3());
        $this->assertNull($items[0]->getCustomerString4());
        $this->assertNull($items[0]->getCustomerString5());
        $this->assertNull($items[0]->getCustomerNumber1());
        $this->assertNull($items[0]->getCustomerNumber2());
        $this->assertNull($items[0]->getCustomerNumber3());
        $this->assertNull($items[0]->getCustomerNumber4());
    }

    public function testAddArray()
    {
        $item = $this->options;
        $this->bag->replace(array($item));
        $items = $this->bag->all();

        $this->assertTrue($items[0] instanceof Item);
        $this->assertSame('Donation', $items[0]->getName());
        $this->assertSame('Fundraiser', $items[0]->getDescription());
        $this->assertSame(1, $items[0]->getQuantity());
        $this->assertSame(1.45, $items[0]->getPrice());
        $this->assertSame('1400/BHL00///BOOK///', $items[0]->getReference());
        $this->assertSame('ABCDEF', $items[0]->getAdditionalReference());
        $this->assertSame('12345', $items[0]->getFundCode());
        $this->assertSame('Stay a while, and listen', $items[0]->getNarrative());
    }

    public function testAddItemInterface()
    {
        $item = new BaseItem($this->options);
        $this->bag->replace(array($item));
        $items = $this->bag->all();

        $this->assertTrue($items[0] instanceof Item);
        $this->assertSame('Donation', $items[0]->getName());
        $this->assertSame('Fundraiser', $items[0]->getDescription());
        $this->assertSame(1, $items[0]->getQuantity());
        $this->assertSame(1.45, $items[0]->getPrice());
        $this->assertNull($items[0]->getReference());
        $this->assertNull($items[0]->getAdditionalReference());
        $this->assertNull($items[0]->getFundCode());
        $this->assertNull($items[0]->getNarrative());
    }
}
