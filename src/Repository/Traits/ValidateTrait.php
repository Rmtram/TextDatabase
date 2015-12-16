<?php

namespace Rmtram\TextDatabase\Repository\Traits;

use Rmtram\TextDatabase\Entity\BaseEntity;

trait ValidateTrait
{
    /**
     * @param BaseEntity $entity
     * @return bool
     */
    public function validate(BaseEntity $entity)
    {
        $fields = $this->repository->getFields();
        foreach ($fields as $fieldName => $variable) {
            $v = new \ReflectionMethod($variable, 'prohibit');
            $v->setAccessible(true);
            if ($v->invoke($variable, $entity->{$fieldName}, $this->repository)) {
                return false;
            }
        }
        return true;
    }

}