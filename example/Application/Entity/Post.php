<?php

namespace Rmtram\TextDatabase\Example\Entity;

use Rmtram\TextDatabase\Entity\BaseEntity;
use Rmtram\TextDatabase\Example\Repository\PostEntityManager;

class Post extends BaseEntity
{
    /**
     * @var int
     */
    public $id;

    /**
     * @var string
     */
    public $title;

    /**
     * @var string
     */
    public $description;

    /**
     * @var int
     */
    public $user_id;

    /**
     * @var \DateTime|string
     */
    public $created_at;

    /**
     * @var \DateTime|string
     */
    public $updated_at;

    /**
     * @var string
     */
    protected static $entityManager = PostEntityManager::class;
}