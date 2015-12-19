<?php

namespace Rmtram\TextDatabase\EntityManager\Query;

use Rmtram\TextDatabase\Entity\BaseEntity;
use Rmtram\TextDatabase\EntityManager\BaseEntityManager;
use Rmtram\TextDatabase\EntityManager\Memory;
use Rmtram\TextDatabase\EntityManager\Traits\AssertTrait;
use Rmtram\TextDatabase\Writer\StorageWriter;

class SaveQuery
{

    use AssertTrait;

    /**
     * save update operator
     */
    const OPERATOR_ADD    = 1;

    /**
     * save add operator.
     */
    const OPERATOR_UPDATE = 2;

    /**
     * @var string
     */
    private $entityManager;
    /**
     * @var array
     */
    private $unique = ['primary', 'unique'];

    /**
     * @var int
     */
    private $lastIndex;

    /**
     * @var array
     */
    private $lastEntity;

    /**
     * @var array
     */
    private $items;

    /**
     * constructor.
     * @param string $entityManager
     * @param Memory $memory
     */
    public function __construct($entityManager, Memory $memory)
    {
        $this->assertEntityManager($entityManager);
        $this->entityManager = $entityManager;
        $this->memory = $memory;
        $this->items = $memory->get();
    }

    /**
     * @param BaseEntity $entity
     * @return mixed
     */
    public function execute(BaseEntity $entity)
    {
        if (false === $this->validate($entity)) {
            return false;
        }
        $operator = $this->substitution($entity);
        if ($this->write()) {
            $this->memory->set($this->items);
            return true;
        }
        if (false === $this->rollback($operator)) {
            throw new \RuntimeException('fail rollback.');
        }
        return false;
    }

    /**
     * @param BaseEntity $entity
     * @return bool
     */
    public function validate(BaseEntity $entity)
    {
        /** @var BaseEntityManager $entityManager */
        $entityManager = $this->entityManager;
        $fields = $entityManager::getFields();
        foreach ($fields as $fieldName => $variable) {
            $v = new \ReflectionMethod($variable, 'prohibit');
            $v->setAccessible(true);
            if ($v->invoke($variable, $entity->{$fieldName})) {
                return false;
            }
        }
        return true;
    }

    /**
     * @param $operator
     * @return bool
     */
    private function rollback($operator)
    {
        if (!is_null($this->lastIndex)) {
            if ($operator === static::OPERATOR_ADD) {
                unset($this->items[$this->lastIndex]);
                return true;
            }
            else if ($operator === static::OPERATOR_UPDATE) {
                $this->items[$this->lastIndex] = $this->lastEntity;
                return true;
            }
        }
        return false;
    }

    /**
     * @param BaseEntity $entity
     * @return int
     */
    private function substitution(BaseEntity $entity)
    {
        /** @var BaseEntityManager $entityManager */
        $entityManager = $this->entityManager;
        $fields = $entityManager::getFields();
        $row = $entity();
        foreach ($fields as $field) {
            $property = $field();
            $name = $property['name'];
            if ($this->unique($property, $row, $name, $entity, $entityManager)) {
                return static::OPERATOR_UPDATE;
            }
            if ($this->isAutoIncrement($property)) {
                $last = $entityManager::find()
                    ->order([$name => 'desc'])
                    ->first();
                if (empty($last)) {
                    $row[$name] = 1;
                }
                else {
                    $row[$name] = $last->$name + 1;
                }
            }
        }
        $this->items[] = $row;
        end($this->items);
        $this->lastIndex = key($this->items);
        return static::OPERATOR_ADD;
    }

    /**
     * @param $property
     * @param $row
     * @param $entity
     * @param BaseEntityManager $entityManager
     * @return bool
     */
    private function unique($property, $row, $name, $entity, $entityManager) {
        if (!$this->isUnique($property) || empty($entity->$name)) {
            return false;
        }
        $index = $entityManager::find()
            ->where($name, $entity->$name)
            ->uniqueIndex();

        if (false !== $index && isset($this->items[$index])) {
            $this->lastIndex  = $index;
            $this->lastEntity = $this->items[$index];
            $this->items[$index] = $row;
            return true;
        }
        return false;
    }

    /**
     * @return bool
     */
    private function write()
    {
        /** @var BaseEntityManager $entityManager */
        $entityManager = $this->entityManager;
        $writer = new StorageWriter($entityManager::getTable(), $this->items);
        return $writer->write(true);
    }

    /**
     * @param array $property
     * @return bool
     */
    private function isAutoIncrement(array $property)
    {
        if (!isset($property['attributes']['autoIncrement'])) {
            return false;
        }
        if (true === $property['attributes']['autoIncrement']) {
            return true;
        }
        return false;
    }

    /**
     * @param array $property
     * @return bool
     */
    private function isUnique(array $property)
    {
        $attr = $property['attributes'];
        foreach ($this->unique as $key) {
            if (isset($attr[$key]) && true === $attr[$key]) {
                return true;
            }
        }
        return false;
    }
}