<?php

namespace Rmtram\TextDatabase\EntityManager;

interface RelationInterface
{
    /**
     * Get belong to entity.
     * @return array
     */
    public static function getBelongsTo();

    /**
     * Get has one entity.
     * @return array
     */
    public static function getHasOne();

    /**
     * Get has many entity.
     * @return array
     */
    public static function getHasMany();

    /**
     * Get many to many entity.
     * @return array
     */
    public static function getManyToMany();
}