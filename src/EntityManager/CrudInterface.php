<?php

namespace Rmtram\TextDatabase\EntityManager;

use Rmtram\TextDatabase\Entity\BaseEntity;
use Rmtram\TextDatabase\EntityManager\Query\SelectQuery;

interface CrudInterface
{
    /**
     * Find Entity.
     * @return SelectQuery
     */
    public static function find();

    /**
     * Save Entity.
     * insert if there is no primary key.
     * update If there is primary key.
     * @param BaseEntity $entity
     * @return bool
     */
    public static function save(BaseEntity $entity);

    /**
     * Delete Entity.
     * argument is null => Delete all.
     * argument is BaseEntity => Delete correspond entity.
     * argument is array (e.g. ['id' => 1]) => Delete correspond entity.
     * @param array|BaseEntity|null $target
     * @return bool
     */
    public static function delete($target = null);
}