<?php

namespace Omnipay\CapitaPay360;

use Omnipay\Common\ItemBag as BaseItemBag;
use Omnipay\Common\ItemInterface;
use Omnipay\CapitaPay360\Item;

/**
 * Capita Pay360 collection of sale line items
 */
class ItemBag extends BaseItemBag
{
    /**
     * Override {@see Omnipay\Common\ItemBag::add()} to use custom {@see Omnipay\CapitaPay360\Item} class
     */
    public function add($item)
    {
        if ($item instanceof Item) {
            $this->items[] = $item;
        } elseif ($item instanceof ItemInterface) {
            $this->items[] = new Item(array(
                'name'        => $item->getName(),
                'description' => $item->getDescription(),
                'quantity'    => $item->getQuantity(),
                'price'       => $item->getPrice(),
            ));
        } else {
            $this->items[] = new Item($item);
        }
    }
}
