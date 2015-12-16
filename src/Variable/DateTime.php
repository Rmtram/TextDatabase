<?php

namespace Rmtram\TextDatabase\Variable;

use Respect\Validation\Validator;
use Rmtram\TextDatabase\Repository\BaseRepository;

class DateTime extends Variable
{

    /**
     * @var array
     */
    private $format = ['y-m-d', 'y-m-d h:i:s'];

    /**
     * @param string|\DateTime $value
     * @return bool
     */
    protected function prohibit($value)
    {
        if ($value instanceof \DateTime) {
            $value = $value->format('Y-m-d H:i:s');
        }
        else {
            if (!is_string($value)) {
                return true;
            }
            foreach ($this->format as $format) {
                if (!Validator::date($format)->validate($value)) {
                    return true;
                }
            }
        }
        return parent::prohibit($value);
    }

}