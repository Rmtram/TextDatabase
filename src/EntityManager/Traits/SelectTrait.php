<?php

namespace Rmtram\TextDatabase\EntityManager\Traits;

use Braincrafted\ArrayQuery\Operator\EqualOperator;
use Braincrafted\ArrayQuery\Operator\GreaterOperator;
use Braincrafted\ArrayQuery\Operator\GreaterOrEqualOperator;
use Braincrafted\ArrayQuery\Operator\LikeOperator;
use Braincrafted\ArrayQuery\Operator\NotEqualOperator;
use Braincrafted\ArrayQuery\Operator\NotLikeOperator;
use Braincrafted\ArrayQuery\WhereEvaluation;

/**
 * Trait SelectTrait
 * @package Rmtram\TextDatabase\EntityManager\Query
 */
trait SelectTrait
{

    /**
     * @var array
     */
    protected $select = [];

    /**
     * @var array
     */
    protected $where = [];

    /**
     * @var array
     */
    protected $order = [];

    /**
     * @var WhereEvaluation
     */
    protected $whereEvaluation;

    /**
     * initialize evaluation
     */
    protected function initializeEvaluation()
    {
        $whereEvaluation = new WhereEvaluation();
        $whereEvaluation
            ->addOperator(new EqualOperator())
            ->addOperator(new NotEqualOperator())
            ->addOperator(new GreaterOperator())
            ->addOperator(new GreaterOrEqualOperator())
            ->addOperator(new LikeOperator())
            ->addOperator(new NotLikeOperator())
            ->addOperator(new EqualOperator());
        $this->whereEvaluation = $whereEvaluation;
    }

    /**
     * Filter of the field of the return value
     * @param array $select
     * @return $this
     */
    public function select(array $select)
    {
        $this->select = $select;
        return $this;
    }

    /**
     * Specify the search condition.
     * @param string $key
     * @param string $value
     * @param string $operator
     * @return $this
     */
    public function where($key, $value, $operator = '=')
    {
        $this->where[] = compact('key', 'value', 'operator');
        return $this;
    }

    /**
     * Sort from the field of direction.
     * @param array $order
     * @return $this
     */
    public function order(array $order)
    {
        $this->order = $order;
        return $this;
    }

    /**
     * @param $item
     * @return bool
     * @throws \Braincrafted\ArrayQuery\Exception\UnkownOperatorException
     */
    protected function evaluate(array $item)
    {
        foreach ($this->where as $clause) {
            if (false === $this->whereEvaluation->evaluate($item, $clause)) {
                return false;
            }
        }
        return true;
    }

    /**
     * @param array $items
     * @return array
     */
    protected function sort(&$items)
    {
        if (empty($items)) {
            return $items;
        }
        $tmp = [];
        foreach ($items as $d) {
            foreach ($this->order as $key => $val) {
                $tmp[$key][] = $d[$key];
            }
        }
        $args = [];
        foreach ($this->order as $key => $val) {
            $args[$key] = $tmp[$key];
            $args[] = strtolower($val) === 'desc' ? SORT_DESC : SORT_ASC;
        }
        $args[] = &$items;
        call_user_func_array('array_multisort', $args);
        return $items;
    }

}