<?php

namespace Yandex\Market\Partner\Models;

use Yandex\Common\Model;

class Outlet extends Model
{

    protected $id = null;

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
}
