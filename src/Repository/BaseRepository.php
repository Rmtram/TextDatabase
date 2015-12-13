<?php

namespace Rmtram\TextDatabase\Repository;

use Rmtram\TextDatabase\Entity\BaseEntity;
use Rmtram\TextDatabase\Exceptions\NotVariableClassException;
use Rmtram\TextDatabase\Reader\Reader;
use Rmtram\TextDatabase\Repository\Query\Selector;
use Rmtram\TextDatabase\Repository\Traits\AssertTrait;
use Rmtram\TextDatabase\Repository\Traits\ValidateTrait;
use Rmtram\TextDatabase\Variable\Variable;
use Rmtram\TextDatabase\Writer\StorageWriter;

abstract class BaseRepository
{

    use AssertTrait;
    use ValidateTrait;

    protected $fields;

    protected $table;

    protected $entityClass;

    protected $data;

    public function __construct()
    {
        $this->assertTable($this->table);
        $this->assertEntity($this->entityClass);
        $this->loadOfSchema();
        $this->loadOfStorage();
    }

    public function find()
    {
        return new Selector($this->data);
    }

    public function save(BaseEntity $entity)
    {
        $this->assertEntity($entity, $this->entityClass);
        if ($this->validate($entity)) {
            return false;
        }
        $this->data[] = $entity();
        $writer = new StorageWriter($this->table, $this->data);
        return $writer->write();
    }

    private function loadOfStorage()
    {
        $reader = new Reader();
        $data = $reader
            ->throws(false)
            ->getStorage($this->table);
        $this->data = $data;
    }

    private function loadOfSchema()
    {
        $reader = new Reader();
        $schema = $reader->getSchema($this->table);
        foreach ($schema as $variable) {
            if (!is_a($variable['type'], Variable::class)) {
                throw new NotVariableClassException(
                    'not variable class ' . $variable['type']);
            }
            $name = $variable['name'];
            /** @var Variable $variableObject */
            $variableObject = new $variable['type']($name);
            $refMethod = new \ReflectionMethod($variableObject, 'setAttributes');
            $refMethod->invoke($variable['attribute']);
            $this->fields[$name] = $variableObject;
        }
    }

    protected function __sleep()
    {
        return $this->data;
    }
}
