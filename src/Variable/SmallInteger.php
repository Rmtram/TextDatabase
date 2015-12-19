<?php

namespace Rmtram\TextDatabase\Variable;

class SmallInteger extends AbstractInteger
{

    /**
     * @var integer
     */
    protected $max = 32767;

    /**
     * @var integer
     */
    protected $min = -32767;

}