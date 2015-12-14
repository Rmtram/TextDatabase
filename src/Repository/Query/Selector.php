<?php

namespace Rmtram\TextDatabase\Repository\Query;

use Braincrafted\ArrayQuery\Operator\EqualOperator;
use Braincrafted\ArrayQuery\Operator\GreaterOperator;
use Braincrafted\ArrayQuery\Operator\LikeOperator;
use Braincrafted\ArrayQuery\Operator\NotEqualOperator;
use Braincrafted\ArrayQuery\SelectEvaluation;
use Braincrafted\ArrayQuery\WhereEvaluation;

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
     * @param mixed $key
     * @param string $operator
     * @param string $value
     * @param array $filters
     * @return $this
     */
    public function where($key, $operator = '=', $value, array $filters = array())
    {
        $this->query->where($key, $value, $operator, $filters);
        return $this;
    }

    /**
     * @return int
     */
    public function count()
    {
        return count($this->all());
    }

    /**
     * @return array[\Rmtram\TextDatabase\Entity\BaseEntity]
     */
    public function all()
    {
        return $this->query
            ->from($this->data)
            ->get();
    }

    /**
     * @return \Rmtram\TextDatabase\Entity\BaseEntity
     */
    public function first()
    {
        return $this->query
            ->from($this->data)
            ->get(true);
    }

}