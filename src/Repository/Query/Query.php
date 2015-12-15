<?php

namespace Rmtram\TextDatabase\Repository\Query;

use Braincrafted\ArrayQuery\ArrayQuery;
use Braincrafted\ArrayQuery\SelectEvaluation;
use Braincrafted\ArrayQuery\WhereEvaluation;
use Rmtram\TextDatabase\Entity\BaseEntity;
use Rmtram\TextDatabase\Exceptions\NotEntityClassException;

class Query extends ArrayQuery
{

    /**
     * @var array
     */
    protected $from =  [];

    /**
     * @var string
     */
    protected $entityClass;

    /**
     * @param $entityClass
     * @param SelectEvaluation $selectEvaluation
     * @param WhereEvaluation $whereEvaluation
     */
    public function __construct($entityClass,
                                  SelectEvaluation $selectEvaluation,
                                  WhereEvaluation $whereEvaluation)
    {
        if (!is_a($entityClass, BaseEntity::class, true)) {
            throw new NotEntityClassException($entityClass);
        }
        $this->entityClass = $entityClass;
        parent::__construct($selectEvaluation, $whereEvaluation);
        $this->select(['*']);
    }

    /**
     * @param array $from
     * @return $this
     */
    public function from(array $from)
    {
        $this->from = $from;
        return $this;
    }

    /**
     * @return bool|int
     */
    public function getIndexNumber()
    {
        foreach ($this->from as $index => $item) {
            if (true === $this->evaluateWhere($item)) {
                return $index;
            }
        }
        return false;
    }

    /**
     * @param bool $first
     * @return array|BaseEntity
     */
    public function get($first = false)
    {
        $result = [];
        foreach ($this->from as $item) {
            if (true === $this->evaluateWhere($item)) {
                $resultItem = $this->evaluateSelect($item, false);
                $entity = $this->createEntity($resultItem);
                if (true === $first) {
                    return $entity;
                }
                $result[] = $entity;
            }
        }
        return $result;
    }

    /**
     * @param array $item
     * @return BaseEntity
     */
    private function createEntity(array $item)
    {
        /** @var BaseEntity $entity */
        $entity = new $this->entityClass();
        $entity->setArray($item);
        return $entity;
    }

}