<?php

namespace Rmtram\TextDatabase\UnitTest\Schema\Variable;

use Rmtram\TextDatabase\Connection;
use Rmtram\TextDatabase\Reader\Reader;
use Rmtram\TextDatabase\Schema\Builder;
use Rmtram\TextDatabase\Schema\Schema;
use Rmtram\TextDatabase\UnitTest\Fixtures\Entity\Test;
use Rmtram\TextDatabase\UnitTest\Fixtures\EntityManager\TestEntityManager;

class SmallIntegerTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        require_once __DIR__ . '/../../fixtures/Entity/Test.php';
        require_once __DIR__ . '/../../fixtures/EntityManager/TestEntityManager.php';
        Connection::setPath(__DIR__ . '/../../fixtures/storage/');
    }

    public function testFieldNameWithId()
    {
        $this->createTable();
        $reader = new Reader();
        $schema = $reader->getSchema('tests');
        $property = $schema[0];
        $this->assertEquals($property['name'], 'id', 'property name id');
    }

    public function testFieldAttributeWithIdInPrimary()
    {
        $this->createTable();
        $reader = new Reader();
        $schema = $reader->getSchema('tests');
        $property = $schema[0];
        $attr = $property['attributes'];
        $this->assertTrue($attr['unique'], 'bad!! attr id of unique in false');
    }

    public function testFieldAttributeWithIdInUnique()
    {
        $this->createTable();
        $reader = new Reader();
        $schema = $reader->getSchema('tests');
        $property = $schema[0];
        $attr = $property['attributes'];
        $this->assertTrue($attr['primary'], 'bad!! attr id of primary false');
    }

    public function testFieldAttributeWithIdInNull()
    {
        $this->createTable();
        $reader = new Reader();
        $schema = $reader->getSchema('tests');
        $property = $schema[0];
        $attr = $property['attributes'];
        $this->assertFalse($attr['null'], 'bad!! attr id of null in true');
    }

    public function testFieldAttributeWithIdInAutoIncrementIsFalse()
    {
        $this->createTable(false);
        $reader = new Reader();
        $schema = $reader->getSchema('tests');
        $property = $schema[0];
        $attr = $property['attributes'];
        $this->assertFalse($attr['autoIncrement'], 'bad!! attr id of autoIncrement true');
    }

    public function testFieldAttributeWithIdInAutoIncrementIsTrue()
    {
        $this->createTable(true);
        $reader = new Reader();
        $schema = $reader->getSchema('tests');
        $property = $schema[0];
        $attr = $property['attributes'];
        $this->assertTrue($attr['autoIncrement'], 'bad!! attr id of autoIncrement false');
    }

    public function testSaveById1()
    {
        $this->createTable(false);
        $test = new Test();
        $test->id = 1;
        $bool = TestEntityManager::save($test);
        $this->assertTrue($bool);
    }

    public function testSaveById1000000()
    {
        $this->createTable(false);
        $test = new Test();
        $test->id = 10000000000;
        $bool = TestEntityManager::save($test);
        $this->assertFalse($bool);
    }

    public function tearDown()
    {
        $this->clear();
    }

    private function createTable($autoIncrement = false)
    {
        return Builder::make()
            ->table('tests', function(Schema $schema) use($autoIncrement) {
                $schema->smallInteger('id')
                    ->primary()
                    ->notNull()
                    ->autoIncrement($autoIncrement);
            });
    }

    private function clear()
    {
        $testSchemaFile  = __DIR__ . '/../../fixtures/storage/tests.rtb';
        $testStorageFile = __DIR__ . '/../../fixtures/storage/tests.rtd';
        if (is_file($testSchemaFile)) {
            unlink($testSchemaFile);
        }
        if (is_file($testStorageFile)) {
            unlink($testStorageFile);
        }
    }
}