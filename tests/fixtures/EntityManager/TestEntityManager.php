<?php

namespace Rmtram\TextDatabase\UnitTest\Fixtures\EntityManager;

use Rmtram\TextDatabase\EntityManager\BaseEntityManager;
use Rmtram\TextDatabase\UnitTest\Fixtures\Entity\Test;

class TestEntityManager extends BaseEntityManager
{
    /**
     * @var string
     */
    protected $table = 'tests';

    /**
     * @var string
     */
    protected $entity = Test::class;

}