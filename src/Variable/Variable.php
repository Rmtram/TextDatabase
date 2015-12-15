<?php

namespace Rmtram\TextDatabase\Variable;

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

    protected $forces = [
        'primary' => [
            'unique' => true,
            'null'   => false
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
        $this->setName($name);
        $this->mergeAddAttributesToDefault();
        $this->loadOfDefaultAttributes();
    }

    private function forceChange()
    {
        foreach ($this->attributes as $key => &$dist) {
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
     * Validate.
     * @param mixed $expression
     * @return bool
     */
    abstract protected function validate($expression);

    /**
     * @param $name
     */
    private function setName($name)
    {
        Validator::notEmpty()->assert($name);
        $this->name = $name;
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

    private function loadOfDefaultAttributes()
    {
        if (empty($this->defaultAttributes)) {
            return;
        }
        $this->attributes = $this->defaultAttributes;
    }
}