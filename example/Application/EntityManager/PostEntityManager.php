<?php

namespace Rmtram\TextDatabase\Example\Repository;

use Rmtram\TextDatabase\EntityManager\BaseEntityManager;
use Rmtram\TextDatabase\Example\Entity\Comment;
use Rmtram\TextDatabase\Example\Entity\Post;
use Rmtram\TextDatabase\Example\Entity\User;

class PostEntityManager extends BaseEntityManager
{
    /**
     * @var string
     */
    protected $table  = 'posts';

    /**
     * @var string
     */
    protected $entity = Post::class;

    /**
     * @var array
     */
    protected $belongsTo = [
        'user' => User::class
    ];

    /**
     * @var array
     */
    protected $hasMany = [
        'comments' => Comment::class
    ];
}