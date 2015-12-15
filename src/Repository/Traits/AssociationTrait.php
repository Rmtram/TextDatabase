<?php
/**
 * Created by PhpStorm.
 * User: noguhiro
 * Date: 15/12/15
 * Time: 15:17
 */

namespace Rmtram\TextDatabase\Repository\Traits;


trait AssociationTrait
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
    public function getBelongsTo()
    {
        return $this->belongsTo;
    }

    /**
     * @return array
     */
    public function getHasMany()
    {
        return $this->hasMany;
    }

    /**
     * @return array
     */
    public function getHasOne()
    {
        return $this->hasOne;
    }

    /**
     * @return array
     */
    public function getManyToMany()
    {
        return $this->manyToMany;
    }
}