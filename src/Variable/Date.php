<?php

namespace Rmtram\TextDatabase\Variable;

use Respect\Validation\Validator;
use Rmtram\TextDatabase\Repository\BaseRepository;

class Date extends Variable
{
    /**
     * Date format.
     * @var string
     */
    private $format = 'Y-m-d';

    /**
     * @param string|\DateTime $value
     * @return bool
     */
    protected function prohibit($value)
    {
        if ($value instanceof \DateTime) {
            $value = $value->format('Y-m-d');
        }
        else {
            if (!is_string($value) ||
                !Validator::date($this->format)->validate($value)) {
                return true;
            }
        }
        return parent::prohibit($value);
    }
}