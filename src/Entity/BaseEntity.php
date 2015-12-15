<?php

namespace Rmtram\TextDatabase\Entity;

use Doctrine\Common\Inflector\Inflector;
use Rmtram\TextDatabase\Exceptions\NotRepositoryClassException;
use Rmtram\TextDatabase\Repository\BaseRepository;
use Traversable;

class BaseEntity implements \IteratorAggregate
{

    /**
     * @var BaseRepository
     */
    protected $repository;

    /**
     * constructor.
     */
    public function __construct()
    {
        $this->loadOfRepository();
    }

    /**
     * @param string $field
     * @param string $val
     */
    public function set($field, $val)
    {
        if (property_exists($this, $field)) {
            $this->{$field} = $val;
        }
    }

    /**
     * @param array $item
     * @return $this
     */
    public function setArray(array $item)
    {
        foreach ($item as $field => $val) {
            $this->set($field, $val);
        }
        return $this;
    }

    /**
     * @param string $key
     * @return array|BaseEntity
     */
    public function __get($key)
    {
        $belongsTo = $this->repository->getBelongsTo();
        if (array_key_exists($key, $belongsTo)) {
            $repositoryClass = $belongsTo[$key];
            /** @var BaseRepository $repo */
            $repo = new $repositoryClass();
            $relationKey = $this->createRelationKey($key);
            return $repo->find()
                ->where('id', $this->$relationKey)
                ->first();
        }
        $hasMany = $this->repository->getHasMany();
        if (array_key_exists($key, $hasMany)) {
            $repositoryClass = $hasMany[$key];
            /** @var BaseRepository $repo */
            $repo = new $repositoryClass();
            $relationKey = $this->createRelationKey($this->repository->getTable());
            var_dump($relationKey, $this->id);
            return $repo->find()
                ->where($relationKey, $this->id)
                ->all();
        }
        $hasOne = $this->repository->getHasOne();
        if (array_key_exists($key, $hasOne)) {
            $repositoryClass = $hasOne[$key];
            /** @var BaseRepository $repo */
            $repo = new $repositoryClass();
            $relationKey = $this->createRelationKey($key);
            return $repo->find()
                ->where($relationKey, $this->id)
                ->first();
        }
        return null;
    }

    /**
     * @param string $key
     * @return string
     */
    private function createRelationKey($key)
    {
        return sprintf('%s_id', Inflector::singularize($key));
    }

    /**
     * @return array
     */
    public function __invoke()
    {
        $ret = [];
        foreach ($this as $fieldName => $val) {
            $ret[$fieldName] = $val;
        }
        return $ret;
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Retrieve an external iterator
     * @link http://php.net/manual/en/iteratoraggregate.getiterator.php
     * @return Traversable An instance of an object implementing <b>Iterator</b> or
     * <b>Traversable</b>
     */
    public function getIterator()
    {
        return new \ArrayIterator($this);
    }

    /**
     * load repository.
     */
    private function loadOfRepository()
    {
        if (empty($this->repository)) {
            throw new \RuntimeException('undefined repository.');
        }

        if (!is_a($this->repository, BaseRepository::class, true)) {
            throw new NotRepositoryClassException($this->repository);
        }

        if (is_string($this->repository)) {
            $this->repository = new $this->repository();
        }
    }
}