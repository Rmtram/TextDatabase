<?php

namespace Rmtram\TextDatabase\Repository;

use Cake\Filesystem\File;
use Rmtram\TextDatabase\Connection;
use Rmtram\TextDatabase\Entity\BaseEntity;
use Rmtram\TextDatabase\Exceptions\NotVariableClassException;
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
        $this->loadOfAttributes();
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

    private function loadOfAttributes()
    {
        $fields = $this->loadOfFields();
        foreach ($fields as $field) {
            if (!is_a($field['type'], Variable::class)) {
                throw new NotVariableClassException(
                    'not variable class ' . $field['type']);
            }
            $name = $field['name'];
            /** @var Variable $variable */
            $variable = new $field['type']($name);
            $refMethod = new \ReflectionMethod($variable, 'setAttributes');
            $refMethod->invoke($field['attribute']);
            $this->fields[$name] = $variable;
        }
    }

    /**
     * @return mixed
     */
    private function loadOfFields()
    {
        $file = new File($this->getSchemaPath());
        if (!$file->readable()) {
            throw new \RuntimeException(
                'not exists table ' . $this->table);
        }
        $serialize = $file->read();
        $fields = unserialize($serialize);
        if (empty($fields)) {
            throw new \UnexpectedValueException('empty fields');
        }
        return $fields;
    }

    private function getSchemaPath()
    {
        return sprintf('%s%s.%s',
            Connection::getPath(),
            $this->table,
            Connection::getSchemaExtension());
    }

    protected function __sleep()
    {
        return $this->data;
    }
}
