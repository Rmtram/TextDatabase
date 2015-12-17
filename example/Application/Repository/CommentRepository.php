<?php

namespace Rmtram\TextDatabase\Example\Repository;

use Rmtram\TextDatabase\Example\Entity\Comment;
use Rmtram\TextDatabase\Example\Entity\Post;
use Rmtram\TextDatabase\Repository\BaseRepository;

class CommentRepository extends BaseRepository
{
    protected $table  = 'comments';

    protected $entity = Comment::class;

    protected $belongsTo = [
        'post' => Post::class
    ];
}