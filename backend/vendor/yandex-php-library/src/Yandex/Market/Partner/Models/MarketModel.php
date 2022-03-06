<?php

namespace Yandex\Market\Partner\Models;

abstract class MarketModel
{

    protected $mappingClasses = [];

    /**
     * Constructor
     *
     * @param array $data
     */
    public function __construct($data = [])
    {
        $this->fromArray($data);
    }

    /**
     * Set from array
     *
     * @param array $data
     * @return $this
     */
    public function fromArray($data)
    {
        foreach ($data as $key => $val) {
            if (is_int($key)) {
                if (method_exists($this, "add")) {
                    $this->add($val);
                }
            }
        
            if (property_exists($this, $key)) {
                if (isset($this->mappingClasses[$key])) {
                    $this->{$key} = new $this->mappingClasses[$key]($val);
                    if (method_exists($this->{$key}, "getAll")) {
                        $this->{$key} = $this->{$key}->getAll();
                    }
                } else {
                    $this->{$key} = $val;
                }
            }
        }
        return $this;
    }

    /**
     * Set from json
     *
     * @param string $json
     * @return $this
     */
    public function fromJson($json)
    {
        $this->fromArray(json_decode($json, true));
        return $this;
    }

    /**
     * Get array from object
     *
     * @return array
     */
    public function toArray()
    {
        return $this->toArrayRecursive($this);
    }

    /**
     * Get array from object
     *
     * @return string
     */
    public function toJson()
    {
        return json_encode($this->toArrayRecursive($this));
    }

    /**
     * Get array from object
     *
     * @param array|object $data
     * @return array
     */
    protected function toArrayRecursive($data)
    {
        if (is_array($data) || is_object($data)) {
            $result = [];
            foreach ($data as $key => $value) {
                if ($key === "mappingClasses") {
                    continue;
                }
                if (is_object($value) && method_exists($value, "getAll")) {
                    $result[$key] = $this->toArrayRecursive($value->getAll());
                } else {
                    if ($value !== null) {
                        $result[$key] = $this->toArrayRecursive($value);
                    }
                }
            }
            return $result;
        }
        return $data;
    }
}
