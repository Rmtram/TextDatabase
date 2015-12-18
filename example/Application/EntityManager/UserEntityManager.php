<?php

namespace Rmtram\TextDatabase\Example\EntityManager;

use Rmtram\TextDatabase\EntityManager\BaseEntityManager;
use Rmtram\TextDatabase\Example\Entity\Post;
use Rmtram\TextDatabase\Example\Entity\User;

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
        'posts' => Post::class
    ];

}