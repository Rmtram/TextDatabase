<?php

namespace Rmtram\TextDatabase\Repository\Query;

use Braincrafted\ArrayQuery\ArrayQuery;
use Braincrafted\ArrayQuery\SelectEvaluation;
use Braincrafted\ArrayQuery\WhereEvaluation;

/**
 * Class SelectQuery
 * @package Rmtram\TextDatabase\Repository\Query
 */
class Selector
{
    /**
     * @var ArrayQuery
     */
    private $query;

    /**
     * @var array
     */
    private $data;

    /**
     * constructor.
     * @param array $data
     */
    public function __construct(array &$data)
    {
        $this->data = $data;
        $this->query = new ArrayQuery(
            new SelectEvaluation(),
            new WhereEvaluation()
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
     * @return array
     */
    public function all()
    {
        return $this->query
            ->from($this->data)
            ->findAll();
    }

    /**
     * @return array
     */
    public function first()
    {
        return $this->query
            ->from($this->data)
            ->findOne();
    }

}