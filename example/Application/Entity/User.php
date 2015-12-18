<?php

namespace Rmtram\TextDatabase\Example\Entity;

use Rmtram\TextDatabase\Entity\BaseEntity;
use Rmtram\TextDatabase\Example\EntityManager\UserEntityManager;

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
     * @var \DateTime|string
     */
    public $created_at;

    /**
     * @var \DateTime|string
     */
    public $updated_at;

    /**
     * @var string
     */
    protected static $entityManager = UserEntityManager::class;
}