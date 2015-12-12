<?php

namespace Rmtram\TextDatabase\Repository\Traits;

use Rmtram\TextDatabase\Entity\BaseEntity;

trait ValidateTrait
{

    public function validate(BaseEntity $entity)
    {
        foreach ($this->fields as $fieldName => $variable) {
            $v = new \ReflectionMethod($variable, 'validate');
            $v->setAccessible(true);
            if (!$v->invoke($entity->{$fieldName})) {
                return false;
            }
        }
        return true;
    }

}