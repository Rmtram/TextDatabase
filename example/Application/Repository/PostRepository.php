<?php

namespace Rmtram\TextDatabase\Example\Repository;

use Rmtram\TextDatabase\Example\Entity\Comment;
use Rmtram\TextDatabase\Example\Entity\Post;
use Rmtram\TextDatabase\Example\Entity\User;
use Rmtram\TextDatabase\Repository\BaseRepository;

class PostRepository extends BaseRepository
{
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