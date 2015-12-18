<?php

namespace Rmtram\TextDatabase\EntityManager\Query;

use Rmtram\TextDatabase\Entity\BaseEntity;
use Rmtram\TextDatabase\EntityManager\ShareStorage;
use Rmtram\TextDatabase\EntityManager\Traits\AssertTrait;
use Rmtram\TextDatabase\EntityManager\Traits\SelectTrait;

/**
 * Class Queries
 * @package Rmtram\TextDatabase\EntityManager\Query
 */
class SelectQuery implements QueryInterface
{

    use SelectTrait, AssertTrait;

    /**
     * @var string
     */
    protected $classEntity;

    /**
     * @var array
     */
    protected $items;

    /**
     * constructor.
     * @param $classEntity
     * @param ShareStorage $storage
     */
    public function __construct($classEntity, ShareStorage $storage)
    {
        $this->assertEntity($classEntity);
        $this->initializeEvaluation();
        $this->storage = $storage;
        $this->classEntity = $classEntity;
    }


    /**
     * Get items.
     * @return array
     */
    public function all()
    {
        return $this->get(false);
    }

    /**
     * Get item.
     * @return BaseEntity
     */
    public function first()
    {
        return $this->get(true);
    }

    /**
     * @return bool|int
     */
    public function uniqueIndex()
    {
        $items = $this->storage->get();
        foreach ($items as $index => $item) {
            if (true === $this->evaluate($item)) {
                return $index;
            }
        }
        return false;
    }

    /**
     * Run the filter of data.
     * @param bool $first
     * @return array
     */
    protected function get($first = false)
    {
        if (!empty($this->order)) {
            $ret = $this->orderExecute($first);
        }
        else {
            $ret = $this->notOrderExecute($first);
        }

        return $ret ?: null;
    }

    /**
     * Create Entity.
     * @param array $item
     * @return BaseEntity
     */
    protected function createEntity(array $item)
    {
        return (new $this->classEntity())
            ->setArray($item);
    }

    /**
     * @param $first
     * @return array|BaseEntity
     */
    protected function orderExecute($first)
    {
        $tmp = [];
        $items = $this->storage->get();
        foreach ($items as $item) {
            if (true === $this->evaluate($item)) {
                $tmp[] = $item;
            }
        }
        $entities = [];
        $this->sort($tmp);
        foreach ($tmp as $item) {
            $entity = $this->createEntity($item);
            if (true === $first) {
                return $entity;
            }
            $entities[] = $entity;
        }
        return $entities;
    }

    /**
     * @param $first
     * @return array|BaseEntity
     */
    protected function notOrderExecute($first)
    {
        $entities = [];
        $items = $this->storage->get();
        foreach ($items as $item) {
            if (true === $this->evaluate($item)) {
                $entity = $this->createEntity($item);
                if (true === $first) {
                    return $entity;
                }
                $entities[] = $entity;
            }
        }
        return $entities;
    }
}