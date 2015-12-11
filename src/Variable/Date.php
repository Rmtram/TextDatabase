<?php

namespace Rmtram\TextDatabase\Variable;

class Date extends Variable
{
    /**
     * Date format.
     * @var string
     */
    private $format = 'y-m-d';

    /**
     * @param string|\DateTime $expression
     * @return bool
     */
    protected function validate($expression)
    {
        if ($expression instanceof \DateTime) {
            return true;
        }
        if (!is_string($expression)) {
            return false;
        }
        if (Validator::date($this->format)->validate($expression)) {
            return true;
        }
        return false;
    }
}