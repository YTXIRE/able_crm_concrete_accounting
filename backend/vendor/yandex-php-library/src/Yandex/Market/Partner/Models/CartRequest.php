<?php

namespace Yandex\Market\Partner\Models;

use Yandex\Market\Partner\Models\Items;
use Yandex\Market\Partner\Models\Delivery;
use Yandex\Common\Model;

class CartRequest extends Model
{

    protected $currency = null;

    protected $items = null;

    protected $delivery = null;

    protected $mappingClasses = [
        'items' => 'Yandex\Market\Partner\Models\Items',
        'delivery' => 'Yandex\Market\Partner\Models\Delivery'
    ];

    protected $propNameMap = [];

    /**
     * Retrieve the currency property
     *
     * @return string|null
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * Set the currency property
     *
     * @param string $currency
     * @return $this
     */
    public function setCurrency($currency)
    {
        $this->currency = $currency;
        return $this;
    }

    /**
     * Retrieve the items property
     *
     * @return Items|null
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * Set the items property
     *
     * @param Items $items
     * @return $this
     */
    public function setItems($items)
    {
        $this->items = $items;
        return $this;
    }

    /**
     * Retrieve the delivery property
     *
     * @return Delivery|null
     */
    public function getDelivery()
    {
        return $this->delivery;
    }

    /**
     * Set the delivery property
     *
     * @param Delivery $delivery
     * @return $this
     */
    public function setDelivery($delivery)
    {
        $this->delivery = $delivery;
        return $this;
    }
}
