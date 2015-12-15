<?php

namespace Rmtram\TextDatabase\Variable;

use Respect\Validation\Validator;

class DateTime extends Variable
{

    /**
     * @var array
     */
    private $format = ['y-m-d', 'y-m-d h:i:s'];

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
        foreach ($this->format as $format) {
            if (Validator::date($format)->validate($expression)) {
                return true;
            }
        }
        return false;
    }

}