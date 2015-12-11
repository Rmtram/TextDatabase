<?php

namespace Rmtram\TextDatabase\Repository;

use Rmtram\TextDatabase\Connection;
use Rmtram\TextDatabase\Entity\BaseEntity;
use Rmtram\TextDatabase\Exceptions\BadPropertyException;

abstract class BaseRepository
{

    protected $fields;

    protected $table;

    protected $entityClass;

    public function __construct()
    {
        $this->assertTable($this->table);
        $this->assertEntity($this->entityClass);
        $this->loadOfAttributes();
    }

    public function save(BaseEntity $entity)
    {
        $this->assertEntity($entity, $this->entityClass);

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
        $file = sprintf('%s%s.rtb',
            Connection::getPath(), $this->table);
        if (!is_file($file)) {
            throw new \RuntimeException(
                'not exists table ' . $this->table);
        }
        $serialize = file_get_contents($file);
        $attributes = unserialize($serialize);
        if (empty($attributes)) {
            throw new \UnexpectedValueException('empty table attributes');
        }
        foreach ($attributes as $attribute) {
            $this->fields[$attribute['name']] = $attribute['type'];
        }
    }

}