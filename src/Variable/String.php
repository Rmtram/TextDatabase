<?php

namespace Rmtram\TextDatabase\Variable;
use Rmtram\TextDatabase\Validation;

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
        $v = new Validation($size);
        $v->modeAssert();
        $v->notEmpty();
        $v->regex('/^[0-9]+$/');
        $v->max(static::MAX_SIZE);
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
    }
}