<?php

namespace Rmtram\TextDatabase\Example\Repository;

use Rmtram\TextDatabase\EntityManager\BaseEntityManager;
use Rmtram\TextDatabase\Example\Entity\Comment;
use Rmtram\TextDatabase\Example\Entity\Post;

class CommentEntityManager extends BaseEntityManager
{
    /**
     * @var string
     */
    protected $table  = 'comments';

    /**
     * @var string
     */
    protected $entity = Comment::class;

    /**
     * @var array
     */
    protected $belongsTo = [
        'post' => Post::class
    ];
}