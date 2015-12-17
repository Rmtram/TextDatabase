<?php

use Rmtram\TextDatabase\Example\Repository\UserRepository;

$userRepository = new UserRepository();
$users = $userRepository->find()->all();
