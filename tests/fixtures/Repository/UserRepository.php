<?php

namespace Rmtram\TextDatabase\UnitTest\Fixtures\Repository;

use Rmtram\TextDatabase\Repository\BaseRepository;
use Rmtram\TextDatabase\UnitTest\Fixtures\Entity\Book;
use Rmtram\TextDatabase\UnitTest\Fixtures\Entity\User;

class UserRepository extends BaseRepository
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