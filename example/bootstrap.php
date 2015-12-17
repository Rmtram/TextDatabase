<?php

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/Application/Repository/UserRepository.php';
require __DIR__ . '/Application/Repository/PostRepository.php';
require __DIR__ . '/Application/Repository/CommentRepository.php';
require __DIR__ . '/Application/Entity/User.php';
require __DIR__ . '/Application/Entity/Post.php';
require __DIR__ . '/Application/Entity/Comment.php';

use Rmtram\TextDatabase\Connection;

Connection::setPath(__DIR__ . '/storage/');

printf('use memory: %s <br/>', memory_get_usage());