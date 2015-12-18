<?php

namespace Rmtram\TextDatabase\EntityManager\Query;

use Rmtram\TextDatabase\Entity\BaseEntity;

interface QueryInterface
{
    /**
     * Filter of the field of the return value
     * @param array $select
     * @return $this
     */
    public function select(array $select);

    /**
     * Specify the search condition.
     * @param string $key
     * @param string $value
     * @param string $operator
     * @return $this
     */
    public function where($key, $value, $operator = '=');

    /**
     * Sort from the field of direction.
     * @param array $order
     * @return $this
     */
    public function order(array $order);

}