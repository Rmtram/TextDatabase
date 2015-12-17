<?php

namespace Rmtram\TextDatabase\UnitTest\Repository;

use Rmtram\TextDatabase\Connection;
use Rmtram\TextDatabase\UnitTest\Fixtures\Repository\UserRepository;
use Rmtram\TextDatabase\Writer\StorageWriter;

class DeleteTest extends \PHPUnit_Framework_TestCase
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

    public function testDeleteUserById1()
    {
        $repository = new UserRepository();
        $repository->delete(['id' => 1]);
        $user = $repository->find()
            ->where('id', 1)
            ->first();
        $this->assertNull($user);
    }

    public function testDeleteUserByEntity()
    {
        $repository = new UserRepository();
        $user = $repository->find()->where('id', 1)->first();
        $this->assertNotNull($user);
        $repository->delete($user);
        $user = $repository->find()->where('id', 1)->first();
        $this->assertNull($user);
    }

    public function testDeleteUsers()
    {
        $repository = new UserRepository();
        $repository->delete();
        $users = $repository->find()->all();
        $this->assertNull($users);
    }

    public function testDeleteUserByName1()
    {
        $repository = new UserRepository();
        $repository->delete(['name' => 'user1']);
        $user = $repository->find()->where('name', 'user1')->first();
        $this->assertNull($user);
    }

    public function testNotDeleteUserByName()
    {
        $repository = new UserRepository();
        $repository->delete(['name' => 'user']);
        $user = $repository->find()->where('name', 'user1')->first();
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