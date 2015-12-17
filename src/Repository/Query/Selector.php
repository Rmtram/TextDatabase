<?php

namespace Rmtram\TextDatabase\Repository\Query;

use Braincrafted\ArrayQuery\Operator\EqualOperator;
use Braincrafted\ArrayQuery\Operator\GreaterOperator;
use Braincrafted\ArrayQuery\Operator\LikeOperator;
use Braincrafted\ArrayQuery\Operator\NotEqualOperator;
use Braincrafted\ArrayQuery\SelectEvaluation;
use Braincrafted\ArrayQuery\WhereEvaluation;
use Rmtram\TextDatabase\Entity\BaseEntity;
use Rmtram\TextDatabase\Writer\StorageWriter;

/**
 * Class SelectQuery
 * @package Rmtram\TextDatabase\Repository\Query
 */
class Selector
{
    /**
     * @var Query
     */
    private $query;

    /**
     * @var array
     */
    private $data;

    /**
     * constructor.
     * @param $entityClass
     * @param array $data
     */
    public function __construct($entityClass, array &$data)
    {
        $this->data = $data;
        $we = new WhereEvaluation();
        $we->addOperator(new EqualOperator());
        $we->addOperator(new GreaterOperator());
        $we->addOperator(new LikeOperator());
        $we->addOperator(new NotEqualOperator());
        $this->query = new Query(
            $entityClass,
            new SelectEvaluation(),
            $we
        );
    }

    /**
     * @param array $fields
     * @return $this
     */
    public function select(array $fields)
    {
        $this->query->select($fields);
        return $this;
    }

    /**
     * @param array $order
     * @return $this
     */
    public function order(array $order) {
        $this->query->order($order);
        return $this;
    }

    /**
     * @param mixed $key
     * @param string $value
     * @param string $operator
     * @return $this
     */
    public function where($key, $value, $operator = '=')
    {
        $this->query->where($key, $value, $operator);
        return $this;
    }

    /**
     * @return array|bool
     */
    protected function delete()
    {
        $items = $this->query
            ->from($this->data)
            ->delete();
        if (false === $items) {
            return false;
        }
        return $items;
    }

    /**
     * @return bool|int
     */
    public function index()
    {
        return $this->query
            ->from($this->data)
            ->getIndex();
    }

    /**
     * @return int
     */
    public function count()
    {
        return count($this->all());
    }

    /**
     * @return array[BaseEntity]|null
     */
    public function all()
    {
        return $this->fetch(false);
    }

    /**
     * @return BaseEntity|null
     */
    public function first()
    {
        return $this->fetch(true);
    }

    /**
     * @param bool $first
     * @return array|BaseEntity|null
     */
    private function fetch($first)
    {
        $ret = $this->query
            ->from($this->data)
            ->get($first);
        return !empty($ret) ? $ret : null;
    }

}