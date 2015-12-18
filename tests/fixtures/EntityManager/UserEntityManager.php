<?php

namespace Rmtram\TextDatabase\UnitTest\Fixtures\EntityManager;

use Rmtram\TextDatabase\EntityManager\BaseEntityManager;
use Rmtram\TextDatabase\UnitTest\Fixtures\Entity\Book;
use Rmtram\TextDatabase\UnitTest\Fixtures\Entity\User;

class UserEntityManager extends BaseEntityManager
{
    /**
     * @var string
     */
    protected $table = 'users';

    /**
     * @var string
     */
    protected $entity = User::class;

    /**
     * @var array
     */
    protected $hasMany = [
        'books' => Book::class
    ];
}