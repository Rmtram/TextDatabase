<?php

namespace Rmtram\TextDatabase\EntityManager\Traits;

use Rmtram\TextDatabase\Entity\BaseEntity;
use Rmtram\TextDatabase\EntityManager\BaseEntityManager;
use Rmtram\TextDatabase\Exceptions\BadPropertyException;

trait AssertTrait
{
    /**
     * @param string $table
     */
    protected function assertTable($table)
    {
        if (empty($table) || !is_string($table)) {
            throw new BadPropertyException('bad invalid table');
        }
    }

    /**
     * @param $entityManager
     * @param $actual
     */
    protected function assertEntityManager($entityManager, $actual = BaseEntityManager::class)
    {
        if (!is_a($entityManager, $actual, true)) {
            throw new BadPropertyException('bad invalid entity manager => ' . $entityManager);
        }
    }

    /**
     * @param string $entity
     * @param string $actual
     */
    protected function assertEntity($entity, $actual = BaseEntity::class)
    {
        if (!is_a($entity, $actual, true)) {
            throw new BadPropertyException('bad invalid entity => ' . $entity);
        }
    }
}