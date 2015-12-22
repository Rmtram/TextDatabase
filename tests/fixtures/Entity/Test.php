<?php

namespace Rmtram\TextDatabase\UnitTest\Fixtures\Entity;

use Rmtram\TextDatabase\Entity\BaseEntity;
use Rmtram\TextDatabase\UnitTest\Fixtures\EntityManager\TestEntityManager;

class Test extends BaseEntity
{
    /**
     * @var int
     */
    public $id;

    /**
     * @var string
     */
    protected static $entityManager = TestEntityManager::class;
}