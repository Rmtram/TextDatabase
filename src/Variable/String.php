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
        $this->setAttribute('length', $size);
        return $this;
    }

    /**
     * Validate.
     * @param mixed $expression
     * @return bool
     */
    protected function validate($expression)
    {
        return true;
    }

}