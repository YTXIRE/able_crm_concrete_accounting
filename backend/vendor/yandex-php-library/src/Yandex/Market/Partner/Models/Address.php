<?php

namespace Yandex\Market\Partner\Models;

use Yandex\Common\Model;

class Address extends Model
{

    protected $country = null;

    protected $postcode = null;

    protected $city = null;

    protected $subway = null;

    protected $street = null;

    protected $house = null;

    protected $block = null;

    protected $entrance = null;

    protected $entryphone = null;

    protected $floor = null;

    protected $apartment = null;

    protected $recipient = null;

    protected $phone = null;

    protected $mappingClasses = [];

    protected $propNameMap = [];

    /**
     * Retrieve the country property
     *
     * @return string|null
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Set the country property
     *
     * @param string $country
     * @return $this
     */
    public function setCountry($country)
    {
        $this->country = $country;
        return $this;
    }

    /**
     * Retrieve the postcode property
     *
     * @return string|null
     */
    public function getPostcode()
    {
        return $this->postcode;
    }

    /**
     * Set the postcode property
     *
     * @param string $postcode
     * @return $this
     */
    public function setPostcode($postcode)
    {
        $this->postcode = $postcode;
        return $this;
    }

    /**
     * Retrieve the city property
     *
     * @return string|null
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set the city property
     *
     * @param string $city
     * @return $this
     */
    public function setCity($city)
    {
        $this->city = $city;
        return $this;
    }

    /**
     * Retrieve the subway property
     *
     * @return string|null
     */
    public function getSubway()
    {
        return $this->subway;
    }

    /**
     * Set the subway property
     *
     * @param string $subway
     * @return $this
     */
    public function setSubway($subway)
    {
        $this->subway = $subway;
        return $this;
    }

    /**
     * Retrieve the street property
     *
     * @return string|null
     */
    public function getStreet()
    {
        return $this->street;
    }

    /**
     * Set the street property
     *
     * @param string $street
     * @return $this
     */
    public function setStreet($street)
    {
        $this->street = $street;
        return $this;
    }

    /**
     * Retrieve the house property
     *
     * @return string|null
     */
    public function getHouse()
    {
        return $this->house;
    }

    /**
     * Set the house property
     *
     * @param string $house
     * @return $this
     */
    public function setHouse($house)
    {
        $this->house = $house;
        return $this;
    }

    /**
     * Retrieve the block property
     *
     * @return string|null
     */
    public function getBlock()
    {
        return $this->block;
    }

    /**
     * Set the block property
     *
     * @param string $block
     * @return $this
     */
    public function setBlock($block)
    {
        $this->block = $block;
        return $this;
    }

    /**
     * Retrieve the entrance property
     *
     * @return string|null
     */
    public function getEntrance()
    {
        return $this->entrance;
    }

    /**
     * Set the entrance property
     *
     * @param string $entrance
     * @return $this
     */
    public function setEntrance($entrance)
    {
        $this->entrance = $entrance;
        return $this;
    }

    /**
     * Retrieve the entryphone property
     *
     * @return string|null
     */
    public function getEntryphone()
    {
        return $this->entryphone;
    }

    /**
     * Set the entryphone property
     *
     * @param string $entryphone
     * @return $this
     */
    public function setEntryphone($entryphone)
    {
        $this->entryphone = $entryphone;
        return $this;
    }

    /**
     * Retrieve the floor property
     *
     * @return string|null
     */
    public function getFloor()
    {
        return $this->floor;
    }

    /**
     * Set the floor property
     *
     * @param string $floor
     * @return $this
     */
    public function setFloor($floor)
    {
        $this->floor = $floor;
        return $this;
    }

    /**
     * Retrieve the apartment property
     *
     * @return string|null
     */
    public function getApartment()
    {
        return $this->apartment;
    }

    /**
     * Set the apartment property
     *
     * @param string $apartment
     * @return $this
     */
    public function setApartment($apartment)
    {
        $this->apartment = $apartment;
        return $this;
    }

    /**
     * Retrieve the recipient property
     *
     * @return string|null
     */
    public function getRecipient()
    {
        return $this->recipient;
    }

    /**
     * Set the recipient property
     *
     * @param string $recipient
     * @return $this
     */
    public function setRecipient($recipient)
    {
        $this->recipient = $recipient;
        return $this;
    }

    /**
     * Retrieve the phone property
     *
     * @return string|null
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set the phone property
     *
     * @param string $phone
     * @return $this
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
        return $this;
    }
}
