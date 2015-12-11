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
     */
    protected function setAttributes(array $attributes)
    {
        $this->attributes += $attributes;
    }

    /**
     * @param $key
     * @param $val
     */
    protected function setAttribute($key, $val)
    {
        $this->attributes[$key] = $val;
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