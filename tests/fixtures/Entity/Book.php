<?php

namespace Rmtram\TextDatabase\UnitTest\Fixtures\Entity;

use Rmtram\TextDatabase\Entity\BaseEntity;
use Rmtram\TextDatabase\UnitTest\Fixtures\EntityManager\BookEntityManager;

class Book extends BaseEntity
{
    /**
     * @var int
     */
    public $id;

    /**
     * @var string
     */
    public $title;

    /**
     * @var string
     */
    public $description;

    /**
     * @var int
     */
    public $user_id;

    /**
     * @var string
     */
    protected static $entityManager = BookEntityManager::class;
}