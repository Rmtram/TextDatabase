<?php

namespace Rmtram\TextDatabase\EntityManager\Traits;

trait RelationTrait
{
    /**
     * @var array
     */
    protected $belongsTo = [];

    /**
     * @var array
     */
    protected $hasMany = [];

    /**
     * @var array
     */
    protected $hasOne = [];

    /**
     * @var array
     */
    protected $manyToMany = [];

    /**
     * @return array
     */
    public static function getBelongsTo()
    {
        $static = static::make();
        return $static->belongsTo;
    }

    /**
     * @return array
     */
    public static function getHasMany()
    {
        $static = static::make();
        return $static->hasMany;
    }

    /**
     * @return array
     */
    public static function getHasOne()
    {
        $static = static::make();
        return $static->hasOne;
    }

    /**
     * @return array
     */
    public static function getManyToMany()
    {
        $static = static::make();
        return $static->manyToMany;
    }
}