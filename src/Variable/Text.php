<?php

namespace Rmtram\TextDatabase\Variable;

class Text extends Variable
{
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