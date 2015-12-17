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
    protected $from = [];

    /**
     * @var array
     */
    protected $order = [];

    /**
     * @var array
     */
    protected $select = [];

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
    }

    /**
     * @param array $select
     * @param array|bool $filters
     * @return $this
     */
    public function select($select, $filters = array())
    {
        if (is_array($select) && !empty($select)) {
            $this->select = $select;
        }
        return $this;
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
     * @param array $order
     * @return $this
     */
    public function order(array $order)
    {
        $this->order = $order;
        return $this;
    }

    /**
     * @return bool|int
     */
    public function getIndex()
    {
        foreach ($this->from as $index => $item) {
            if (true === $this->evaluateWhere($item)) {
                return $index;
            }
        }
        return false;
    }

    /**
     * @return array
     */
    public function delete()
    {
        $cache = $this->from;
        $beforeCount = count($this->from);
        foreach ($this->from as $index => $item) {
            if (true === $this->evaluateWhere($item)) {
                unset($this->from[$index]);
            }
        }
        $afterCount = count($this->from);
        if ($beforeCount === $afterCount) {
            $this->from = $cache;
            return false;
        }
        return $this->from;
    }

    /**
     * @param bool $first
     * @return array|BaseEntity
     */
    public function get($first = false)
    {
        $items = [];
        foreach ($this->from as $item) {
            if (true === $this->evaluateWhere($item)) {
                if (true === $first && empty($this->order)) {
                    return $this->createEntity($item);
                }
                $items[] = $item;
            }
        }
        $entities = [];
        if (!empty($this->order)) {
            $this->sort($items);
        }
        foreach ($items as $item) {
            $entity = $this->createEntity($item);
            if (true === $first) {
                return $entity;
            }
            $entities[] = $entity;
        }
        return $entities;
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
        if (!empty($this->select) && !in_array('*', $this->select)) {
            foreach ($this->select as $field) {
                if (property_exists($entity, $field)) {
                    unset($entity->$field);
                }
            }
        }
        return $entity;
    }

    /**
     * @param $result
     * @return mixed
     */
    private function sort(&$result)
    {
        if (empty($result)) {
            return $result;
        }
        $tmp = [];
        foreach ($result as $d) {
            foreach ($this->order as $key => $val) {
                $tmp[$key][] = $d[$key];
            }
        }
        $args = [];
        foreach ($this->order as $key => $val) {
            $args[$key] = $tmp[$key];
            $args[] = strtolower($val) === 'desc' ? SORT_DESC : SORT_ASC;
        }
        $args[] = &$result;
        call_user_func_array('array_multisort', $args);
        return $result;
    }
}