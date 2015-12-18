<?php

namespace Rmtram\TextDatabase\Example\Entity;

use Rmtram\TextDatabase\Entity\BaseEntity;
use Rmtram\TextDatabase\Example\Repository\CommentEntityManager;

class Comment extends BaseEntity
{
    /**
     * @var int
     */
    public $id;

    /**
     * @var string
     */
    public $comment;

    /**
     * @var int
     */
    public $post_id;

    /**
     * @var string|\DateTime
     */
    public $created_at;

    /**
     * @var string|\DateTime
     */
    public $updated_at;

    /**
     * @var string
     */
    protected static $entityManager = CommentEntityManager::class;
}