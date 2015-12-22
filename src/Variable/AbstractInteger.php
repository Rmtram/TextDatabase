<?php

namespace Rmtram\TextDatabase\Variable;


abstract class AbstractInteger extends Variable
{

    /**
     * @var integer
     */
    protected $max;

    /**
     * @var integer
     */
    protected $min;

    /**
     * @param bool $bool
     * @return $this
     */
    public function autoIncrement($bool = true)
    {
        $this->setAttributeOfBool('autoIncrement', $bool);
        return $this;
    }

    /**
     * @param bool $bool
     * @return $this
     */
    public function unsigned($bool = true)
    {
        $this->setAttributeOfBool('unsigned', $bool);
        return $this;
    }

    /**
     * @param mixed $value
     * @return bool
     */
    protected function prohibit($value)
    {
        if (true === $this->getAttribute('autoIncrement')) {
            return false;
        }

        if (is_array($value) || is_object($value)) {
            return true;
        }

        $primary  = $this->getAttribute('primary');
        $unsigned = $this->getAttribute('unsigned');
        $pattern = false === $primary && false === $unsigned ? '/^-{0,1}[0-9]+$/' : '/^[0-9]+$/';

        if (!preg_match($pattern, $value)) {
            return true;
        }

        $min = true === $unsigned ? 0 : $this->min;
        $max = $this->max;

        if (!$this->between($value, $min, $max)) {
            return true;
        }

        return parent::prohibit($value);
    }

}