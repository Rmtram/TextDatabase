<?php

namespace Rmtram\TextDatabase\Repository;

use Rmtram\TextDatabase\Entity\BaseEntity;
use Rmtram\TextDatabase\Exceptions\NotVariableClassException;
use Rmtram\TextDatabase\Reader\Reader;
use Rmtram\TextDatabase\Repository\Query\Save;
use Rmtram\TextDatabase\Repository\Query\Selector;
use Rmtram\TextDatabase\Repository\Traits\AssertTrait;
use Rmtram\TextDatabase\Repository\Traits\AssociationTrait;
use Rmtram\TextDatabase\Repository\Traits\ValidateTrait;
use Rmtram\TextDatabase\Variable\Variable;

/**
 * Class BaseRepository
 * @package Rmtram\TextDatabase\Repository
 */
abstract class BaseRepository
{

    use AssertTrait, ValidateTrait, AssociationTrait;

    /**
     * @var array
     */
    protected $fields = [];

    /**
     * @var string
     */
    protected $table;

    /**
     * @var string
     */
    protected $entityClass;

    /**
     * @var array
     */
    protected $data = [];

    /**
     * constructor.
     */
    public function __construct()
    {
        $this->assertTable($this->table);
        $this->assertEntity($this->entityClass);
        $this->loadOfSchema();
        $this->loadOfStorage();
    }

    /**
     * @return array
     */
    public function getFields()
    {
        return $this->fields;
    }

    /**
     * @return string
     */
    public function getTable()
    {
        return $this->table;
    }

    /**
     * @return Selector
     */
    public function find()
    {
        return new Selector($this->entityClass, $this->data);
    }

    /**
     * @param BaseEntity $entity
     * @return bool
     */
    public function save(BaseEntity $entity)
    {
        $this->assertEntity($entity, $this->entityClass);
        return (new Save($this, $this->data))
            ->save($entity);
    }

    /**
     * setup load storage.
     */
    private function loadOfStorage()
    {
        $reader = new Reader();
        $data = $reader
            ->throws(false)
            ->getStorage($this->table);
        if (is_array($data) && !empty($data)) {
            $this->data = $data;
        }
    }

    /**
     * setup load schema.
     */
    private function loadOfSchema()
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
