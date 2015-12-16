<?php

namespace Rmtram\TextDatabase\UnitTest\Repository;

use Rmtram\TextDatabase\Connection;
use Rmtram\TextDatabase\UnitTest\Fixtures\Entity\User;
use Rmtram\TextDatabase\UnitTest\Fixtures\Repository\UserRepository;

class SaveTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        require_once __DIR__ . '/../fixtures/Entity/User.php';
        require_once __DIR__ . '/../fixtures/Repository/UserRepository.php';
        Connection::setPath(__DIR__ . '/../fixtures/storage/');
    }

    public function testCreateOfObject()
    {
        $user = new User();
        $user->id = 1;
        $user->name = 'user1';
        $userRepository = new UserRepository();
        $userRepository->save($user);
        $actual = $userRepository->find()->where('id', 1)->first();

        $this->assertEquals($user->id,   $actual->id);
        $this->assertEquals($user->name, $actual->name);
    }

    public function testCreateOfArray()
    {
        $user = new User();
        $user->setArray([
            'id'   => 1,
            'name' => 'user1'
        ]);
        $userRepository = new UserRepository();
        $userRepository->save($user);
        $actual = $userRepository->find()->where('id', 1)->first();
        $this->assertEquals($user->id,   $actual->id);
        $this->assertEquals($user->name, $actual->name);
    }

    public function testCreateOfObjectWithNonProperty()
    {
        $user = new User();
        $user->id = 1;
        $user->name = 'user1';
        $user->ng = 'ng';
        $userRepository = new UserRepository();
        $userRepository->save($user);
        $user = $userRepository->find()->where('id', 1)->first();
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
        $userRepository = new UserRepository();
        $userRepository->save($user);
        $user = $userRepository->find()->where('id', 1)->first();
        $this->assertEmpty($user->ng);
    }

    public function testCreateWithEmpty()
    {
        $user = new User();
        $user->setArray([
            'id'   => null,
            'name' => null
        ]);
        $userRepository = new UserRepository();
        $this->assertFalse($userRepository->save($user));
    }

    public function testCreate100Times()
    {
        $range = range(1, 100);
        $userRepository = new UserRepository();
        foreach ($range as $index) {
            $user = new User();
            $user->setArray([
                'id'   => $index,
                'name' => 'user' . $index
            ]);
            $userRepository->save($user);
        }
        $count = $userRepository->find()->count();
        $this->assertEquals($count, 100);
    }

    public function testUpdate()
    {
        $user = new User();
        $user->setArray([
            'id'   => 1,
            'name' => 'user1'
        ]);
        $userRepository = new UserRepository();
        $userRepository->save($user);

        $user = $userRepository->find()->where('id', 1)->first();
        $user->name = 'person1';
        $userRepository->save($user);
        /** @var User $user */
        $user = $userRepository->find()->where('id', 1)->first();
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