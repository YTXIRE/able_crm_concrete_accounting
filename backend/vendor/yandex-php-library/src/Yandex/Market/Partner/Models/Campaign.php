<?php

namespace Yandex\Market\Partner\Models;

use Yandex\Common\Model;

class Campaign extends Model
{

    protected $id = null;

    protected $domain = null;

    protected $state = null;

    protected $stateReasons = null;

    protected $mappingClasses = [];

    protected $propNameMap = [];

    /**
     * Retrieve the id property
     *
     * @return int|null
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the id property
     *
     * @param int $id
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Retrieve the domain property
     *
     * @return string|null
     */
    public function getDomain()
    {
        return $this->domain;
    }

    /**
     * Set the domain property
     *
     * @param string $domain
     * @return $this
     */
    public function setDomain($domain)
    {
        $this->domain = $domain;
        return $this;
    }

    /**
     * Retrieve the state property
     *
     * @return int|null
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * Set the state property
     *
     * @param int $state
     * @return $this
     */
    public function setState($state)
    {
        $this->state = $state;
        return $this;
    }

    /**
     * Retrieve the stateReasons property
     *
     * @return array|null
     */
    public function getStateReasons()
    {
        return $this->stateReasons;
    }

    /**
     * Set the stateReasons property
     *
     * @param array $stateReasons
     * @return $this
     */
    public function setStateReasons($stateReasons)
    {
        $this->stateReasons = $stateReasons;
        return $this;
    }
}
