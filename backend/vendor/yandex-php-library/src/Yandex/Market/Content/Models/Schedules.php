<?php

namespace Yandex\Market\Content\Models;

use Yandex\Common\ObjectModel;

class Schedules extends ObjectModel
{
    /**
     * Add review to collection
     *
     * @param Schedule|array $sсhedule
     *
     * @return Schedules
     */
    public function add($sсhedule)
    {
        if (is_array($sсhedule)) {
            $this->collection[] = new Schedule($sсhedule);
        } elseif (is_object($sсhedule) && $sсhedule instanceof Schedule) {
            $this->collection[] = $sсhedule;
        }

        return $this;
    }

    /**
     * Retrieve the collection property
     *
     * @return Schedules
     */
    public function getAll()
    {
        return $this->collection;
    }
}
