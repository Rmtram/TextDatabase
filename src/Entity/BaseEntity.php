<?php

namespace Rmtram\TextDatabase\Entity;

use Doctrine\Common\Inflector\Inflector;
use Rmtram\TextDatabase\EntityManager\BaseEntityManager;
use Rmtram\TextDatabase\EntityManager\Traits\AssertTrait;
use Rmtram\TextDatabase\Variable\Date;
use Rmtram\TextDatabase\Variable\DateTime;
use Traversable;

class BaseEntity implements \IteratorAggregate
{

    use AssertTrait;

    /**
     * @var string
     */
    protected static $entityManager;

    /**
     * constructor.
     */
    public function __construct()
    {
        $this->assertEntityManager(static::$entityManager);
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
        $associations = $this->getAssociations();
        foreach ($associations as $associationName => $association) {
            if (array_key_exists($key, $association)) {
                $entityClass = $association[$key];
                $entity = new $entityClass();

                $property = new \ReflectionProperty($entity, 'entityManager');
                $property->setAccessible(true);
                $entityManager = $property->getValue($entity);

                $this->assertEntityManager($entityManager);
                unset($property, $entity);

                $fetch = $this->getFetchMethodName($associationName);

                return $this->$fetch($entityManager, $key);
            }
        }
        return null;
    }

    /**
     * @return array
     */
    private function getAssociations()
    {
        /** @var BaseEntityManager $entityManager */
        $entityManager = static::$entityManager;
        return [
            'belongsTo' => $entityManager::getBelongsTo(),
            'hasOne'    => $entityManager::getHasOne(),
            'hasMany'   => $entityManager::getHasMany()
        ];
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
     * @param BaseEntityManager $entityManager
     * @param $key
     * @return BaseEntity
     */
    private function fetchBelongsTo($entityManager, $key)
    {
        $key = $this->createRelationKey($key);
        return $entityManager::find()
            ->where('id', $this->{$key})
            ->first();
    }

    /**
     * @param BaseEntityManager $entityManager
     * @param $key
     * @return BaseEntity
     */
    private function fetchHasMany($entityManager, $key)
    {
        /** @var BaseEntityManager $selfEntityManager */
        $selfEntityManager = static::$entityManager;
        $key = $this->createRelationKey($selfEntityManager::getTable());
        return $entityManager::find()
            ->where($key, $this->id)
            ->all();
    }

    /**
     * @param BaseEntityManager $entityManager
     * @param $key
     * @return BaseEntity
     */
    private function fetchHasOne($entityManager, $key)
    {
        /** @var BaseEntityManager $selfEntityManager */
        $selfEntityManager = static::$entityManager;
        $key = $this->createRelationKey($selfEntityManager::getTable());
        return $entityManager::find()
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
        /** @var BaseEntityManager $entityManager */
        $entityManager = static::$entityManager;
        foreach ($this as $fieldName => $val) {
            if ($val instanceof \DateTime) {
                $variable = $entityManager::getField($fieldName);
                if ($variable instanceof Date) {
                    $val = $val->format(Date::FORMAT);
                }
                else if ($variable instanceof DateTime) {
                    $val = $val->format(DateTime::FORMAT);
                }
                else {
                    throw new \UnexpectedValueException(
                        'bad!! variable type in ' . $fieldName);
                }
            }
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

}