<?php

namespace Rmtram\TextDatabase\UnitTest\EntityManager;

use Rmtram\TextDatabase\Connection;
use Rmtram\TextDatabase\UnitTest\Fixtures\EntityManager\UserEntityManager;
use Rmtram\TextDatabase\Writer\StorageWriter;

class ReadTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        require_once __DIR__ . '/../fixtures/Entity/User.php';
        require_once __DIR__ . '/../fixtures/Entity/Book.php';
        require_once __DIR__ . '/../fixtures/EntityManager/UserEntityManager.php';
        require_once __DIR__ . '/../fixtures/EntityManager/BookEntityManager.php';
        Connection::setPath(__DIR__ . '/../fixtures/storage/');
        $this->addFixtures();
    }

    public function testGetUsers()
    {
        $users = UserEntityManager::find()->all();
        $this->assertEquals($users[0]->id, 1);
        $this->assertEquals($users[0]->name, 'user1');
    }

    public function testGetUser()
    {
        $user = UserEntityManager::find()->first();
        $this->assertEquals($user->id, 1);
        $this->assertEquals($user->name, 'user1');
    }

    public function testGetUserById1()
    {
        $user = UserEntityManager::find()->where('id', 1)->first();
        $this->assertEquals($user->id, 1);
        $this->assertEquals($user->name, 'user1');
    }

    public function testGetUserWithBookById1()
    {
        $user = UserEntityManager::find()->where('id', 1)->first();
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

    public function testOrderAsc()
    {
        $user = UserEntityManager::find()->order(['id' => 'asc'])->first();
        $this->assertEquals($user->id, 1);
        $this->assertEquals($user->name, 'user1');
    }

    public function testOrderDesc()
    {
        $user = UserEntityManager::find()->order(['id' => 'desc'])->first();
        $this->assertEquals($user->id, 2);
        $this->assertEquals($user->name, 'user2');
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
        ],[
            'id' => 2,
            'name' => 'user2'
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