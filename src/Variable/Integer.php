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
     * Validate.
     * @param mixed $expression
     * @return bool
     */
    protected function validate($expression)
    {
        return true;
    }

}