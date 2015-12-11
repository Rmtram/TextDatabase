<?php

namespace Rmtram\TextDatabase\Repository;

use Rmtram\TextDatabase\Connection;
use Rmtram\TextDatabase\Entity\BaseEntity;
use Rmtram\TextDatabase\Exceptions\BadPropertyException;
use Rmtram\TextDatabase\Exceptions\NotVariableClassException;
use Rmtram\TextDatabase\Variable\Variable;

abstract class BaseRepository
{

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

    public function save(BaseEntity $entity)
    {
        $this->assertEntity($entity, $this->entityClass);
        if ($this->validate($entity)) {
            return false;
        }
        $this->data[] = $entity();
        serialize($this);
        return true;
    }

    public function validate(BaseEntity $entity)
    {
        foreach ($this->fields as $fieldName => $variable) {
            $v = new \ReflectionMethod($variable, 'validate');
            $v->setAccessible(true);
            if (!$v->invoke($entity->{$fieldName})) {
                return false;
            }
        }
        return true;
    }

    private function assertTable($table)
    {
        if (empty($table) || !is_string($table)) {
            throw new BadPropertyException('bad invalid table');
        }
    }

    private function assertEntity($entity, $actual = BaseEntity::class)
    {
        if (is_a($entity, $actual)) {
            throw new BadPropertyException('bad invalid entity');
        }
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
        $file = sprintf('%s%s.rtb',
            Connection::getPath(), $this->table);
        if (!is_file($file)) {
            throw new \RuntimeException(
                'not exists table ' . $this->table);
        }
        $serialize = file_get_contents($file);
        $fields = unserialize($serialize);
        if (empty($fields)) {
            throw new \UnexpectedValueException('empty fields');
        }
        return $fields;
    }

    protected function __sleep()
    {
        return $this->data;
    }
}