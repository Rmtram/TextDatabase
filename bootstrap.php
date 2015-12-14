<?php

use Rmtram\TextDatabase\Schema\Builder;
use Rmtram\TextDatabase\Schema\Schema;

require __DIR__ . '/vendor/autoload.php';

$path = __DIR__ . DIRECTORY_SEPARATOR . 'tdb' . DIRECTORY_SEPARATOR;
\Rmtram\TextDatabase\Connection::setPath(__DIR__ . '/tdb/');
Builder::make($path)
    ->setOverwrite(true)
    ->table('users', function(Schema $schema) {
    $schema->integer('id');
    $schema->string('title');
});

$reader = new \Rmtram\TextDatabase\Reader\Reader();

//var_dump($reader->getStorage('users'));

class User extends Rmtram\TextDatabase\Entity\BaseEntity {
    public $id;
    public $title;
}

class UserRepo extends Rmtram\TextDatabase\Repository\BaseRepository {
    protected $table = 'users';
    protected $entityClass = User::class;
}
$em = new UserRepo();

/** @var User $user */
$user = $em->find()->first();