<?php

namespace Rmtram\TextDatabase\EntityManager;

use Rmtram\TextDatabase\Entity\BaseEntity;
use Rmtram\TextDatabase\EntityManager\Query\DeleteQuery;
use Rmtram\TextDatabase\EntityManager\Query\SaveQuery;
use Rmtram\TextDatabase\EntityManager\Query\SelectQuery;
use Rmtram\TextDatabase\EntityManager\Traits\AssertTrait;
use Rmtram\TextDatabase\EntityManager\Traits\RelationTrait;
use Rmtram\TextDatabase\Exceptions\NotVariableClassException;
use Rmtram\TextDatabase\Reader\Reader;
use Rmtram\TextDatabase\Variable\Variable;

/**
 * Class BaseEntityManager
 * @package Rmtram\TextDatabase\EntityManager
 */
abstract class BaseEntityManager implements CrudInterface, RelationInterface
{

    use RelationTrait, AssertTrait;

    /**
     * @var array
     */
    private static $instances = [];

    /**
     * @var string
     */
    protected $entity;

    /**
     * @var string
     */
    protected $table;

    /**
     * @var array
     */
    protected $fields = [];


    /**
     * constructor.
     */
    private function __construct()
    {
        $this->assertTable($this->table);
        $this->assertEntity($this->entity);
        $this->initialize();
    }

    /**
     * @return static
     */
    private static function make()
    {
        $subClass = get_called_class();
        if (!isset(self::$instances[$subClass])) {
            self::$instances[$subClass] = new static();
        }
        return self::$instances[$subClass];
    }

    /**
     * @return string
     */
    public static function getTable()
    {
        $static = static::make();
        return $static->table;
    }

    /**
     * @return array
     */
    public static function getFields()
    {
        $static = static::make();
        return $static->fields;
    }

    /**
     * @param string $key
     * @return Variable|null
     */
    public static function getField($key)
    {
        $static = static::make();
        if (isset($static->fields[$key])) {
            return $static->fields[$key];
        }
        return null;
    }

    /**
     * Find Entity.
     * @return SelectQuery
     */
    public static function find()
    {
        $static = static::make();
        $memory = Memory::make($static->table);
        return new SelectQuery($static->entity, $memory);
    }

    /**
     * Save Entity.
     * insert if there is no primary key.
     * update if there is primary key.
     * @param BaseEntity $entity
     * @return bool
     */
    public static function save(BaseEntity $entity)
    {
        $static = static::make();
        $storage = Memory::make($static->table);
        return (new SaveQuery(static::class, $storage))
            ->execute($entity);
    }

    /**
     * Delete Entity.
     * argument is null => Delete all.
     * argument is BaseEntity => Delete correspond entity.
     * argument is array (e.g. ['id' => 1]) => Delete correspond entity.
     * @param array|BaseEntity|null $target
     * @return bool
     */
    public static function delete($target = null)
    {
        $static = static::make();
        $storage = Memory::make($static->table);
        return (new DeleteQuery(static::class, $storage))
            ->execute($target);
    }

    /**
     * setup load schema.
     */
    private function initialize()
    {
        $reader = new Reader();
        $schema = $reader->getSchema($this->table);
        foreach ($schema as $variable) {
            if (!is_a($variable['type'], Variable::class, true)) {
                throw new NotVariableClassException(
                    'not variable class ' . $variable['type']);
            }
            $name = $variable['name'];
            /** @var Variable $variableObject */
            $variableObject = new $variable['type']($name);
            $refMethod = new \ReflectionMethod($variableObject, 'setAttributes');
            $refMethod->setAccessible(true);
            $refMethod->invoke($variableObject, $variable['attributes']);
            $this->fields[$name] = $variableObject;
        }
    }

}