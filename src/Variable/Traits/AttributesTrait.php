<?php

namespace Rmtram\TextDatabase\Variable\Traits;

/**
 * trait AttributesTrait
 * @package Rmtram\TextDatabase\Variable\Traits
 */
trait AttributesTrait
{

    /**
     * @param array $attributes
     * @return $this
     */
    protected function setAttributes(array $attributes)
    {
        $this->attributes = $attributes + $this->attributes;
        return $this;
    }

    /**
     * @param $key
     * @param $bool
     * @return $this
     */
    protected function setAttributeOfBool($key, $bool) {
        if (is_bool($bool)) {
            $this->attributes[$key] = $bool;
        }
        return $this;
    }

    /**
     * @param $key
     * @param $val
     * @return $this
     */
    protected function setAttribute($key, $val)
    {
        $this->attributes[$key] = $val;
        return $this;
    }

    /**
     * @param $key
     * @return bool
     */
    protected function getAttribute($key)
    {
        if (!isset($this->attributes[$key])) {
            return false;
        }
        return $this->attributes[$key];
    }

    /**
     * @return $this
     */
    public function unique()
    {
        $this->setAttribute('unique', true);
        return $this;
    }

    /**
     * @return $this
     */
    public function primary()
    {
        $this->setAttribute('primary', true);
        return $this;
    }

    /**
     * @return $this
     */
    public function null()
    {
        $this->setAttribute('null', true);
        return $this;
    }

    /**
     * @return $this
     */
    public function notNull()
    {
        $this->setAttribute('null', false);
        return $this;
    }

}