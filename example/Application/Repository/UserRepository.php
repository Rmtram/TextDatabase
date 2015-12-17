<?php

namespace Rmtram\TextDatabase\Example\Repository;

use Rmtram\TextDatabase\Example\Entity\Post;
use Rmtram\TextDatabase\Example\Entity\User;
use Rmtram\TextDatabase\Repository\BaseRepository;

class UserRepository extends BaseRepository
{
    protected $table  = 'users';

    protected $entity = User::class;

    protected $hasMany = [
        'posts' => Post::class
    ];
}