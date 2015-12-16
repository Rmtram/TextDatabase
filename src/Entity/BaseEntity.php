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
        $this->association = [
            'belongsTo' => $this->repository->getBelongsTo(),
            'hasOne'    => $this->repository->getHasOne(),
            'hasMany'   => $this->repository->getHasMany()
        ];
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
     * @return array|BaseEntity|null
     */
    public function __get($key)
    {
        foreach ($this->association as $associationName => $association) {
            if (array_key_exists($key, $association)) {
                $entityClass = $association[$key];
                $entity = new $entityClass();
                $property = new \ReflectionProperty($entity, 'repository');
                $property->setAccessible(true);
                /** @var BaseRepository $repository */
                $repository = $property->getValue($entity);
                unset($property, $entity);
                $fetch = $this->getFetchMethodName($associationName);
                return $this->$fetch($repository, $key);
            }
        }
        return null;
    }

    /**
     * @param $associationName
     * @return string
     */
    private function getFetchMethodName($associationName)
    {
        return 'fetch' . ucfirst($associationName);
    }

    /**
     * @param BaseRepository $repository
     * @param $key
     * @return BaseEntity
     */
    private function fetchBelongsTo(BaseRepository $repository, $key)
    {
        $key = $this->createRelationKey($key);
        return $repository->find()
            ->where('id', $this->{$key})
            ->first();
    }

    /**
     * @param BaseRepository $repository
     * @param $key
     * @return BaseEntity
     */
    private function fetchHasMany(BaseRepository $repository, $key)
    {
        $key = $this->createRelationKey($this->repository->getTable());
        return $repository->find()
            ->where($key, $this->id)
            ->all();
    }

    /**
     * @param BaseRepository $repository
     * @param $key
     * @return BaseEntity
     */
    private function fetchHasOne(BaseRepository $repository, $key)
    {
        $key = $this->createRelationKey($this->repository->getTable());
        return $repository->find()
            ->where($key, $this->id)
            ->first();
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