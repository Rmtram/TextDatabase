<?php

namespace Rmtram\TextDatabase\Variable;

class Integer extends Variable
{

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
        if (!preg_match('/^[0-9]+$/', $value)) {
            return true;
        }
        $value = intval($value);
        return parent::prohibit($value);
    }

}