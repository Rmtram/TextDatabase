<?php

namespace Rmtram\TextDatabase\EntityManager\Query;
use Rmtram\TextDatabase\Entity\BaseEntity;
use Rmtram\TextDatabase\EntityManager\BaseEntityManager;
use Rmtram\TextDatabase\EntityManager\ShareStorage;
use Rmtram\TextDatabase\EntityManager\Traits\AssertTrait;
use Rmtram\TextDatabase\EntityManager\Traits\BindTrait;
use Rmtram\TextDatabase\EntityManager\Traits\SelectTrait;
use Rmtram\TextDatabase\Writer\StorageWriter;

/**
 * Class DeleteQuery
 * @package Rmtram\TextDatabase\EntityManager\Query
 */
class DeleteQuery implements QueryInterface
{
    use SelectTrait, AssertTrait;

    /**
     * @var string
     */
    private $entityManager;

    private $storage;

    /**
     * constructor.
     * @param $entityManager
     * @param ShareStorage $storage
     */
    public function __construct($entityManager, ShareStorage $storage)
    {
        $this->assertEntityManager($entityManager);
        $this->initializeEvaluation();
        $this->entityManager = $entityManager;
        $this->storage = $storage;
    }

    /**
     * @param $target
     * @return bool
     */
    public function execute($target)
    {
        $this->target($target);
        $items = $this->storage->get();
        $before = count($items);
        foreach ($items as $index => $item) {
            if (true === $this->evaluate($item)) {
                unset($items[$index]);
            }
        }
        $after = count($items);
        if ($before === $after) {
            return false;
        }
        if ($this->write($items)) {
            $this->storage->set($items);
            return true;
        }
        return false;
    }

    /**
     * @param $items
     * @return bool
     */
    private function write(&$items)
    {
        /** @var BaseEntityManager $entityManager */
        $entityManager = $this->entityManager;
        $writer = new StorageWriter(
            $entityManager::getTable(), $items);
        return $writer->write(true);
    }

    /**
     * @param $target
     */
    private function target($target)
    {
        /** @var BaseEntityManager $entityManager */
        $entityManager = $this->entityManager;
        $fields = $entityManager::getFields();
        if ($target instanceof BaseEntity) {
            foreach ($target as $key => $value) {
                $this->where($key, $value);
            }
        }
        else if (is_array($target) && !empty($target)) {
            foreach ($target as $key => $value) {
                if (!array_key_exists($key, $fields)) {
                    throw new \InvalidArgumentException(
                        $key . ' is not not assignment');
                }
                $this->where($key, $value);
            }
        }
    }

}