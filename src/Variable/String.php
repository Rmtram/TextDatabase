<?php

namespace Rmtram\TextDatabase\Variable;

/**
 * Class String
 * @package Rmtram\TextDatabase\Variable
 */
class String extends Variable
{
    /**
     * String max size.
     */
    const MAX_SIZE = 65536;

    /**
     * @var array
     */
    protected $addDefaultAttributes = [
        'length' => 255
    ];

    /**
     * @param integer|string $size
     * @return $this
     */
    public function length($size)
    {
        if (0 > $size && $size < static::MAX_SIZE) {
            $this->setAttribute('length', $size);
        }
        return $this;
    }

    /**
     * @param mixed $value
     * @return bool
     */
    protected function prohibit($value)
    {
        if (is_array($value) || is_object($value)) {
            return true;
        }
        $maxLength = $this->getAttribute('length');
        if (!is_null($value) && (strlen($value) > $maxLength)) {
            return true;
        }
        $value = $value ? strval($value) : $value;
        return parent::prohibit($value);
    }
}