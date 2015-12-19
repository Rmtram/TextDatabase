<?php

namespace Rmtram\TextDatabase\Variable;

class TinyInteger extends AbstractInteger
{

    /**
     * @var integer
     */
    protected $max = 127;

    /**
     * @var integer
     */
    protected $min = -127;

}