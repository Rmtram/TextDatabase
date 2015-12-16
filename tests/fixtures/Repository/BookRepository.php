<?php

namespace Rmtram\TextDatabase\UnitTest\Fixtures\Repository;

use Rmtram\TextDatabase\Repository\BaseRepository;
use Rmtram\TextDatabase\UnitTest\Fixtures\Entity\Book;
use Rmtram\TextDatabase\UnitTest\Fixtures\Entity\User;

class BookRepository extends BaseRepository
{
    /**
     * @var string
     */
    protected $table = 'books';

    /**
     * @var string
     */
    protected $entityClass = Book::class;

    /**
     * @var array
     */
    protected $belongsTo = [
        'user' => User::class
    ];
}