<?php

namespace Rmtram\TextDatabase\Variable;

class Integer extends AbstractInteger
{
    
    /**
     * @var integer
     */
    protected $max = 2147483647;

    /**
     * @var integer
     */
    protected $min = -2147483647;

}