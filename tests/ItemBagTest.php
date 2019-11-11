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

    /** @var mixed[]  Data to initialize the test item with */
    private $options;

    public function setUp()
    {
        $this->bag = new ItemBag;
        $this->options = array(
            'name' => 'Donation',
            'description' => 'Fundraiser',
            'quantity' => 1,
            'price' => 1.45,
            'reference' => '1400/BHL00///BOOK///',
            'additionalReference' => 'ABCDEF',
            'fundCode' => '12345',
            'narrative' => 'Stay a while, and listen'
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
