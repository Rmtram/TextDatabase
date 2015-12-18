<?php

namespace Rmtram\TextDatabase\UnitTest\Fixtures\Entity;

use Rmtram\TextDatabase\Entity\BaseEntity;
use Rmtram\TextDatabase\UnitTest\Fixtures\EntityManager\UserEntityManager;

class User extends BaseEntity
{
    /**
     * @var int
     */
    public $id;

    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    protected static $entityManager = UserEntityManager::class;
}