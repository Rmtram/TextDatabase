<?php

namespace Rmtram\TextDatabase\Entity;

use Traversable;

class BaseEntity implements \IteratorAggregate
{
    /**
     * @param string $field
     * @param string $val
     */
    public function set($field, $val)
    {
        if (property_exists($this, $field)) {
            $this->{$field} = $val;
        }
    }

    /**
     * @param array $item
     */
    public function setArray(array $item)
    {
        foreach ($item as $field => $val) {
            $this->set($field, $val);
        }
    }

    /**
     * @return array
     */
    public function __invoke()
    {
        $ret = [];
        foreach ($this as $fieldName => $val) {
            $ret[$fieldName] = $val;
        }
        return $ret;
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Retrieve an external iterator
     * @link http://php.net/manual/en/iteratoraggregate.getiterator.php
     * @return Traversable An instance of an object implementing <b>Iterator</b> or
     * <b>Traversable</b>
     */
    public function getIterator()
    {
        return new \ArrayIterator($this);
    }
}