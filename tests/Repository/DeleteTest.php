<?php

namespace Rmtram\TextDatabase\UnitTest\Repository;

use Rmtram\TextDatabase\Connection;
use Rmtram\TextDatabase\EntityManager\ShareStorage;
use Rmtram\TextDatabase\UnitTest\Fixtures\EntityManager\UserEntityManager;
use Rmtram\TextDatabase\UnitTest\Fixtures\Repository\UserRepository;
use Rmtram\TextDatabase\Writer\StorageWriter;

class DeleteTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        require_once __DIR__ . '/../fixtures/Entity/User.php';
        require_once __DIR__ . '/../fixtures/Entity/Book.php';
        require_once __DIR__ . '/../fixtures/EntityManager/UserEntityManager.php';
        require_once __DIR__ . '/../fixtures/EntityManager/BookEntityManager.php';
        Connection::setPath(__DIR__ . '/../fixtures/storage/');
        $this->addFixtures();
        foreach (['users', 'books'] as $table) {
            $share = ShareStorage::make($table);
            $ref = new \ReflectionMethod($share, 'reset');
            $ref->setAccessible(true);
            $ref->invoke($share);
        }

    }

    public function testDeleteUserById1()
    {
        UserEntityManager::delete(['id' => 1]);
        $user = UserEntityManager::find()
            ->where('id', 1)
            ->first();
        $this->assertNull($user);
    }

    public function testDeleteUserByEntity()
    {
        $user = UserEntityManager::find()->where('id', 1)->first();
        $this->assertNotNull($user);
        UserEntityManager::delete($user);
        $user = UserEntityManager::find()->where('id', 1)->first();
        $this->assertNull($user);
    }

    public function testDeleteUsers()
    {
        UserEntityManager::delete();
        $users = UserEntityManager::find()->all();
        $this->assertNull($users);
    }

    public function testDeleteUserByName1()
    {
        UserEntityManager::delete(['name' => 'user1']);
        $user = UserEntityManager::find()->where('name', 'user1')->first();
        $this->assertNull($user);
    }

    public function testNotDeleteUserByName()
    {
        UserEntityManager::delete(['name' => 'user']);
        $user = UserEntityManager::find()->where('name', 'user1')->first();
        $this->assertNotNull($user);
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