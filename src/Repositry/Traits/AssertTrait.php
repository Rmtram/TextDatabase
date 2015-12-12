<?php

namespace Rmtram\TextDatabase\Repository\Traits;

use Rmtram\TextDatabase\Entity\BaseEntity;
use Rmtram\TextDatabase\Exceptions\BadPropertyException;

trait AssertTrait
{
    private function assertTable($table)
    {
        if (empty($table) || !is_string($table)) {
            throw new BadPropertyException('bad invalid table');
        }
    }

    private function assertEntity($entity, $actual = BaseEntity::class)
    {
        if (is_a($entity, $actual)) {
            throw new BadPropertyException('bad invalid entity');
        }
    }
}