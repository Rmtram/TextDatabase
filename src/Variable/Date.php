<?php

namespace Rmtram\TextDatabase\Variable;

use Respect\Validation\Validator;
use Rmtram\TextDatabase\Repository\BaseRepository;

class Date extends Variable
{
    const FORMAT = 'Y-m-d';

    /**
     * Date format.
     * @var string
     */
    private $format = self::FORMAT;

    /**
     * @param string|\DateTime $value
     * @return bool
     */
    protected function prohibit($value)
    {
        if ($value instanceof \DateTime) {
            $value = $value->format(self::FORMAT);
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