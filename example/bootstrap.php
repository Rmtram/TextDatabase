<?php

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/Application/EntityManager/UserEntityManager.php';
require __DIR__ . '/Application/EntityManager/PostEntityManager.php';
require __DIR__ . '/Application/EntityManager/CommentEntityManager.php';
require __DIR__ . '/Application/Entity/User.php';
require __DIR__ . '/Application/Entity/Post.php';
require __DIR__ . '/Application/Entity/Comment.php';

use Rmtram\TextDatabase\Connection;

Connection::setPath(__DIR__ . '/storage/');