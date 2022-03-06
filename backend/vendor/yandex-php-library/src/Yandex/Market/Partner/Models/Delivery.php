<?php

namespace Yandex\Market\Partner\Models;

use Yandex\Market\Partner\Models\Region;
use Yandex\Market\Partner\Models\Address;
use Yandex\Market\Partner\Models\DatesRange;
use Yandex\Common\Model;

class Delivery extends Model
{

    protected $id = null;

    protected $type = null;

    protected $serviceName = null;

    protected $price = null;

    protected $outletId = null;

    protected $region = null;

    protected $address = null;

    protected $dates = null;

    protected $mappingClasses = [
        'region' => 'Yandex\Market\Partner\Models\Region',
        'address' => 'Yandex\Market\Partner\Models\Address',
        'dates' => 'Yandex\Market\Partner\Models\DatesRange'
    ];

    protected $propNameMap = [];

    /**
     * Retrieve the id property
     *
     * @return string|null
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the id property
     *
     * @param string $id
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Retrieve the type property
     *
     * @return string|null
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set the type property
     *
     * @param string $type
     * @return $this
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * Retrieve the serviceName property
     *
     * @return string|null
     */
    public function getServiceName()
    {
        return $this->serviceName;
    }

    /**
     * Set the serviceName property
     *
     * @param string $serviceName
     * @return $this
     */
    public function setServiceName($serviceName)
    {
        $this->serviceName = $serviceName;
        return $this;
    }

    /**
     * Retrieve the price property
     *
     * @return int|null
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set the price property
     *
     * @param int $price
     * @return $this
     */
    public function setPrice($price)
    {
        $this->price = $price;
        return $this;
    }

    /**
     * Retrieve the outletId property
     *
     * @return int|null
     */
    public function getOutletId()
    {
        return $this->outletId;
    }

    /**
     * Set the outletId property
     *
     * @param int $outletId
     * @return $this
     */
    public function setOutletId($outletId)
    {
        $this->outletId = $outletId;
        return $this;
    }

    /**
     * Retrieve the region property
     *
     * @return Region|null
     */
    public function getRegion()
    {
        return $this->region;
    }

    /**
     * Set the region property
     *
     * @param Region $region
     * @return $this
     */
    public function setRegion($region)
    {
        $this->region = $region;
        return $this;
    }

    /**
     * Retrieve the address property
     *
     * @return Address|null
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set the address property
     *
     * @param Address $address
     * @return $this
     */
    public function setAddress($address)
    {
        $this->address = $address;
        return $this;
    }

    /**
     * Retrieve the dates property
     *
     * @return DatesRange|null
     */
    public function getDates()
    {
        return $this->dates;
    }

    /**
     * Set the dates property
     *
     * @param DatesRange $dates
     * @return $this
     */
    public function setDates($dates)
    {
        $this->dates = $dates;
        return $this;
    }
}
