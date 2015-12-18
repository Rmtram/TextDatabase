<?php

namespace Rmtram\TextDatabase\UnitTest\Fixtures\EntityManager;

use Rmtram\TextDatabase\EntityManager\BaseEntityManager;
use Rmtram\TextDatabase\UnitTest\Fixtures\Entity\Book;
use Rmtram\TextDatabase\UnitTest\Fixtures\Entity\User;

class BookEntityManager extends BaseEntityManager
{
    /**
     * @var string
     */
    protected $table = 'books';

    /**
     * @var string
     */
    protected $entity = Book::class;

    /**
     * @var array
     */
    protected $belongsTo = [
        'user' => User::class
    ];

}