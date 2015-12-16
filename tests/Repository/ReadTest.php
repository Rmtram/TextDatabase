<?php

namespace Rmtram\TextDatabase\UnitTest\Repository;

use Rmtram\TextDatabase\Connection;
use Rmtram\TextDatabase\Reader\Reader;
use Rmtram\TextDatabase\UnitTest\Fixtures\Entity\User;
use Rmtram\TextDatabase\UnitTest\Fixtures\Repository\UserRepository;
use Rmtram\TextDatabase\Writer\StorageWriter;

class ReadTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        require_once __DIR__ . '/../fixtures/Entity/User.php';
        require_once __DIR__ . '/../fixtures/Entity/Book.php';
        require_once __DIR__ . '/../fixtures/Repository/UserRepository.php';
        require_once __DIR__ . '/../fixtures/Repository/BookRepository.php';
        Connection::setPath(__DIR__ . '/../fixtures/storage/');
        $this->addFixtures();
    }

    public function testGetUsers()
    {
        $repository = new UserRepository();
        $users = $repository->find()->all();
        $this->assertEquals($users[0]->id, 1);
        $this->assertEquals($users[0]->name, 'user1');
    }

    public function testGetUser()
    {
        $repository = new UserRepository();
        $user = $repository->find()->first();
        $this->assertEquals($user->id, 1);
        $this->assertEquals($user->name, 'user1');
    }

    public function testGetUserById1()
    {
        $repository = new UserRepository();
        $user = $repository->find()->where('id', 1)->first();
        $this->assertEquals($user->id, 1);
        $this->assertEquals($user->name, 'user1');
    }

    public function testGetUserWithBookById1()
    {
        $repository = new UserRepository();
        $user = $repository->find()->where('id', 1)->first();
        $books = $user->books;
        $this->assertEquals($books[0]->id, 1);
        $this->assertEquals($books[1]->id, 2);
        $this->assertEquals($books[0]->title, 'title1');
        $this->assertEquals($books[1]->title, 'title2');
        $this->assertEquals($books[0]->description, 'description1');
        $this->assertEquals($books[1]->description, 'description2');
        $this->assertEquals($books[0]->user_id, 1);
        $this->assertEquals($books[1]->user_id, 1);
    }

    public function tearDown()
    {
        $this->clear();
    }

    private function addFixtures()
    {
        $userWriter = new StorageWriter('users', [[
            'id' => 1,
            'name' => 'user1'
        ]]);
        $bookWriter = new StorageWriter('books', [[
            'id' => 1,
            'title' => 'title1',
            'description' => 'description1',
            'user_id' => 1
        ], [
            'id' => 2,
            'title' => 'title2',
            'description' => 'description2',
            'user_id' => 1
        ]]);
        $userWriter->write(true);
        $bookWriter->write(true);
    }

    private function clear()
    {
        $iterator = new \RecursiveDirectoryIterator(__DIR__ . '/../fixtures/storage/');
        $iterator = new \RecursiveIteratorIterator($iterator);
        /** @var \SplFileInfo $file */
        foreach ($iterator as $file) {
            if ('rtd' === $file->getExtension()) {
                unlink($file->getRealPath());
            }
        }
    }
}