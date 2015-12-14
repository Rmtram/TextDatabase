<?php

namespace Rmtram\TextDatabase\Variable;

class Integer extends Variable
{
    /**
     * @return $this
     */
    public function autoIncrement()
    {
        $this->setAttribute('autoIncrement', true);
        return $this;
    }

    /**
     * @return $this
     */
    public function unsigned()
    {
        $this->setAttribute('unsigned', true);
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