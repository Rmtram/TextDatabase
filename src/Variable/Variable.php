<?php

namespace Rmtram\TextDatabase\Variable;

use Rmtram\TextDatabase\Repository\BaseRepository;
use Rmtram\TextDatabase\Variable\Traits\AttributesTrait;
use Respect\Validation\Validator;

/**
 * Class Variable
 * @package Rmtram\TextDatabase\Variable
 */
abstract class Variable
{
    use AttributesTrait;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var array
     */
    protected $forces = [
        'primary' => [
            'unique'   => true,
            'unsigned' => true,
            'null'     => false
        ]
    ];

    /**
     * @var array
     */
    protected $defaultAttributes = [
        'primary' => false,
        'unique'  => false,
        'null'    => false
    ];

    /**
     * @var array
     */
    protected $addDefaultAttributes = [];

    /**
     * @var array
     */
    private $attributes = [];

    /**
     * @param string $name
     */
    public function __construct($name)
    {
        $this->assertName($name);
        $this->setName($name);
        $this->mergeAddAttributesToDefault();
        $this->loadOfDefaultAttributes();
    }

    /**
     * @return array
     */
    public function __invoke()
    {
        $this->forceChange();
        return [
            'type'       => static::class,
            'name'       => $this->name,
            'attributes' => $this->attributes
        ];
    }

    /**
     * @return string
     */
    protected function getName()
    {
        return $this->name;
    }

    /**
     * check Prohibit value
     * @param mixed $value
     * @return bool
     */
    protected function prohibit($value)
    {
        if (false === $this->getAttribute('null')) {
            if (is_null($value)) {
                return true;
            }
        }
        return false;
    }

    /**
     * @param $val
     * @param $min
     * @param $max
     * @return bool
     */
    protected function between($val, $min, $max)
    {
        return $min >= $val && $val <= $max;
    }

    /**
     * @param $name
     */
    private function setName($name)
    {
        $this->name = $name;
    }

    private function assertName($name)
    {
        Validator::notEmpty()->assert($name);
        Validator::type('string')->assert($name);
        Validator::regex('/^[A-Za-z_]+$/')->assert($name);
    }

    /**
     * merge defaultAttributes, addDefaultAttributes
     * @return void
     */
    private function mergeAddAttributesToDefault()
    {
        $this->defaultAttributes =
            $this->addDefaultAttributes + $this->defaultAttributes;
    }

    /**
     * load attribute
     * @return void
     */
    private function loadOfDefaultAttributes()
    {
        if (empty($this->defaultAttributes)) {
            return;
        }
        $this->attributes = $this->defaultAttributes;
    }

    /**
     * attribute force change.
     * @return void
     */
    private function forceChange()
    {
        foreach ($this->attributes as $key => &$d) {
            if (!array_key_exists($key, $this->forces)) {
                continue;
            }
            if (false === $this->getAttribute($key)) {
                continue;
            }
            foreach ($this->forces[$key] as $attrName => $val) {
                $this->setAttribute($attrName, $val);
            }
        }
    }

}