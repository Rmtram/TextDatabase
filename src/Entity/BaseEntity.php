<?php

namespace Rmtram\TextDatabase\Entity;

use Traversable;

class BaseEntity implements \IteratorAggregate
{

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