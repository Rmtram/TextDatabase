<?php

namespace Rmtram\TextDatabase\UnitTest\Repository;

use Rmtram\TextDatabase\Connection;
use Rmtram\TextDatabase\EntityManager\ShareStorage;
use Rmtram\TextDatabase\UnitTest\Fixtures\Entity\User;
use Rmtram\TextDatabase\UnitTest\Fixtures\EntityManager\UserEntityManager;
use Rmtram\TextDatabase\UnitTest\Fixtures\Repository\UserRepository;

class SaveTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        require_once __DIR__ . '/../fixtures/Entity/User.php';
        require_once __DIR__ . '/../fixtures/EntityManager/UserEntityManager.php';
        Connection::setPath(__DIR__ . '/../fixtures/storage/');
        foreach (['users', 'books'] as $table) {
            $share = ShareStorage::make($table);
            $ref = new \ReflectionMethod($share, 'reset');
            $ref->setAccessible(true);
            $ref->invoke($share);
        }
    }

    public function testCreateOfObject()
    {
        $user = new User();
        $user->name = 'user1';
        UserEntityManager::save($user);
        $expand = UserEntityManager::find()->where('id', 1)->first();

        $this->assertEquals($expand->id, 1);
        $this->assertEquals($expand->name, 'user1');
    }

    public function testCreateOfArray()
    {
        $user = new User();
        $user->setArray([
            'id'   => 1,
            'name' => 'user1'
        ]);
        UserEntityManager::save($user);
        $actual = UserEntityManager::find()->where('id', 1)->first();
        $this->assertEquals($user->id,   $actual->id);
        $this->assertEquals($user->name, $actual->name);
    }

    public function testCreateOfObjectWithNonProperty()
    {
        $user = new User();
        $user->id = 1;
        $user->name = 'user1';
        $user->ng = 'ng';
        UserEntityManager::save($user);
        $user = UserEntityManager::find()->where('id', 1)->first();
        $this->assertEmpty($user->ng);
    }

    public function testCreateOfArrayWithNonProperty()
    {
        $user = new User();
        $user->setArray([
            'id'   => 1,
            'name' => 'user1',
            'ng'   => 'ng'
        ]);
        UserEntityManager::save($user);
        $user = UserEntityManager::find()->where('id', 1)->first();
        $this->assertEmpty($user->ng);
    }

    public function testCreateWithEmpty()
    {
        $user = new User();
        $user->setArray([
            'id'   => null,
            'name' => null
        ]);
        $this->assertFalse(UserEntityManager::save($user));
    }

    public function testCreate100Times()
    {
        $range = range(1, 100);
        foreach ($range as $index) {
            $user = new User();
            $user->setArray([
                'name' => 'user' . $index
            ]);
            UserEntityManager::save($user);
        }
        $users = UserEntityManager::find()
            ->order(['id' => 'asc'])
            ->all();

        $cnt = 1;
        foreach ($users as $user) {
            $this->assertEquals($user->id, $cnt);
            $this->assertEquals($user->name, 'user' . $cnt);
            $cnt++;
        }
    }

    public function testUpdate()
    {
        $user = new User();
        $user->setArray([
            'name' => 'user1'
        ]);
        UserEntityManager::save($user);

        /** @var User $user */
        $user = UserEntityManager::find()->where('id', 1)->first();
        $user->name = 'person1';
        UserEntityManager::save($user);

        /** @var User $user */
        $user = UserEntityManager::find()->where('id', 1)->first();
        $this->assertEquals($user->id, 1);
        $this->assertEquals($user->name, 'person1');
    }

    public function tearDown()
    {
        $this->clear();
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