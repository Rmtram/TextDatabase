<?php

namespace Rmtram\TextDatabase\Schema;
use Rmtram\TextDatabase\Variable\Integer;
use Rmtram\TextDatabase\Variable\String;
use Rmtram\TextDatabase\Variable\Date;
use Rmtram\TextDatabase\Variable\DateTime;
use Rmtram\TextDatabase\Variable\Text;
use Rmtram\TextDatabase\Variable\Variable;

/**
 * Class Schema
 * @package Rmtram\TextDatabase\Schema
 * @method Integer integer(String $name)
 * @method String string(String $name)
 * @method Date date(String $name)
 * @method DateTime dateTime(String $name)
 * @method Text text(String $name)
 */
class Schema
{
    /**
     * @var array
     */
    protected $variables = [];

    /**
     * @var array
     */
    protected $variableClasses = [
        'integer'  => Integer::class,
        'string'   => String::class,
        'date'     => Date::class,
        'dateTime' => DateTime::class,
        'text'     => Text::class
    ];

    /**
     * @param $className
     * @param $args
     * @return Variable
     */
    public function __call($className, $args)
    {
        if (!isset($this->variableClasses[$className])) {
            throw new \BadMethodCallException(
                'undefined variable class ' . $className);
        }
        if (empty($args)) {
            throw new \InvalidArgumentException(
                'bad arguments empty, ' . $className);
        }
        $reflection = new \ReflectionClass(
            $this->variableClasses[$className]);
        $variable = $reflection->newInstanceArgs($args);
        $this->variables[] = $variable;
        return $variable;
    }

    /**
     * @return array
     */
    public function __invoke()
    {
        $fields = [];
        foreach ($this->variables as $variable) {
            $fields[] = $variable();
        }
        return $fields;
    }

    /**
     * @param $key
     * @param $className
     */
    protected function addVariableClass($key, $className)
    {
        if (!is_a($className, Variable::class, true)) {
            throw new \RuntimeException(
                'not variable class ' . $className);
        }
        $this->variableClasses[$key] = $className;
    }

    /**
     * @param $key
     * @return bool
     */
    protected function removeVariableClass($key)
    {
        if (array_key_exists($key, $this->variables)) {
            unset($this->variables[$key]);
            return true;
        }
        return false;
    }
}