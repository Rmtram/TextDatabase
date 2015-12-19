<?php

namespace Rmtram\TextDatabase\Schema;
use Respect\Validation\Validator;
use Rmtram\TextDatabase\Variable\Integer;
use Rmtram\TextDatabase\Variable\SmallInteger;
use Rmtram\TextDatabase\Variable\String;
use Rmtram\TextDatabase\Variable\Date;
use Rmtram\TextDatabase\Variable\DateTime;
use Rmtram\TextDatabase\Variable\Text;
use Rmtram\TextDatabase\Variable\TinyInteger;
use Rmtram\TextDatabase\Variable\Variable;

/**
 * Class Schema
 * @package Rmtram\TextDatabase\Schema
 * @method \Rmtram\TextDatabase\Variable\Integer integer(String $name)
 * @method \Rmtram\TextDatabase\Variable\SmallInteger smallInteger(String $name)
 * @method \Rmtram\TextDatabase\Variable\TinyInteger tinyInteger(String $name)
 * @method \Rmtram\TextDatabase\Variable\String string(String $name)
 * @method \Rmtram\TextDatabase\Variable\Date date(String $name)
 * @method \Rmtram\TextDatabase\Variable\DateTime dateTime(String $name)
 * @method \Rmtram\TextDatabase\Variable\Text text(String $name)
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
    protected $unique = [];

    /**
     * @var array
     */
    protected $variableClasses = [
        'integer'      => Integer::class,
        'smallInteger' => SmallInteger::class,
        'tinyInteger'  => TinyInteger::class,
        'string'       => String::class,
        'text'         => Text::class,
        'date'         => Date::class,
        'dateTime'     => DateTime::class
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
                'bad!! undefined variable class => ' . $className);
        }

        $this->assertArgs($args);

        $this->unique[$args[0]] = true;

        $reflection = new \ReflectionClass(
            $this->variableClasses[$className]);

        /** @var Variable $variable */
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

    /**
     * @param array $args
     */
    private function assertArgs($args)
    {
        if (empty($args[0])) {
            throw new \InvalidArgumentException(
                'bad!! arguments empty => ');
        }
        if (!is_string($args[0])) {
            throw new \InvalidArgumentException(
                'bad!! first arg is string only.'
            );
        }
        if (array_key_exists($args[0], $this->unique)) {
            throw new \InvalidArgumentException(
                'bad!! exists field name => ' . $args[0]);
        }
    }
}