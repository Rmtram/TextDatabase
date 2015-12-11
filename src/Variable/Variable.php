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

    /**
     * @return array
     */
    public function __invoke()
    {
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
        array_merge(
            $this->defaultAttributes,
            $this->addDefaultAttributes);
    }

    private function loadOfDefaultAttributes()
    {
        if (empty($this->defaultAttributes)) {
            return;
        }
        $this->attributes = $this->defaultAttributes;
    }
}