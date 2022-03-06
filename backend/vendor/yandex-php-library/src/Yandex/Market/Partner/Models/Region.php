<?php

namespace Yandex\Market\Partner\Models;

use Yandex\Common\Model;

class Region extends Model
{

    protected $id = null;

    protected $name = null;

    protected $type = null;

    protected $parent = null;

    protected $mappingClasses = [
        'parent' => 'Yandex\Market\Partner\Models\Region'
    ];

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
     * Retrieve the name property
     *
     * @return string|null
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the name property
     *
     * @param string $name
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;
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
     * Retrieve the parent property
     *
     * @return Region|null
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Set the parent property
     *
     * @param Region $parent
     * @return $this
     */
    public function setParent($parent)
    {
        $this->parent = $parent;
        return $this;
    }
}
