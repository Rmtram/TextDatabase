<?php
/**
 * Created by PhpStorm.
 * User: noguhiro
 * Date: 15/12/15
 * Time: 21:14
 */

namespace Rmtram\TextDatabase\UnitTest;


use Rmtram\TextDatabase\Connection;
use Rmtram\TextDatabase\Reader\Reader;
use Rmtram\TextDatabase\Schema\Builder;
use Rmtram\TextDatabase\Schema\Schema;

class SchemaTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        Connection::setPath(__DIR__ . '/fixtures/storage/');
    }

    public function testCreateTableOfInteger()
    {
        $this->assertTrue($this->createTableOfInteger());
    }

    public function testExistsTableSchema()
    {
        $this->createTableOfInteger();
        $reader = new Reader();
        $schema = $reader->getSchema('tests');
        $this->assertNotEmpty($schema, 'bad!! empty schema');
    }

    public function testFieldNameWithId()
    {
        $this->createTableOfInteger();
        $reader = new Reader();
        $schema = $reader->getSchema('tests');
        $property = $schema[0];
        $this->assertEquals($property['name'], 'id', 'property name id');
    }

    public function testFieldAttributeWithIdInPrimary()
    {
        $this->createTableOfInteger();
        $reader = new Reader();
        $schema = $reader->getSchema('tests');
        $property = $schema[0];
        $attr = $property['attributes'];
        $this->assertTrue($attr['unique'], 'bad!! attr id of unique in false');
    }

    public function testFieldAttributeWithIdInUnique()
    {
        $this->createTableOfInteger();
        $reader = new Reader();
        $schema = $reader->getSchema('tests');
        $property = $schema[0];
        $attr = $property['attributes'];
        $this->assertTrue($attr['primary'], 'bad!! attr id of primary false');
    }

    public function testFieldAttributeWithIdInNull()
    {
        $this->createTableOfInteger();
        $reader = new Reader();
        $schema = $reader->getSchema('tests');
        $property = $schema[0];
        $attr = $property['attributes'];
        $this->assertFalse($attr['null'], 'bad!! attr id of null in true');
    }

    public function testFieldAttributeWithIdInAutoIncrementIsFalse()
    {
        $this->createTableOfInteger(false);
        $reader = new Reader();
        $schema = $reader->getSchema('tests');
        $property = $schema[0];
        $attr = $property['attributes'];
        $this->assertTrue($attr['autoIncrement'], 'bad!! attr id of autoIncrement false');
    }

    public function testFieldAttributeWithIdInAutoIncrementIsTrue()
    {
        $this->createTableOfInteger(true);
        $reader = new Reader();
        $schema = $reader->getSchema('tests');
        $property = $schema[0];
        $attr = $property['attributes'];
        $this->assertTrue($attr['autoIncrement'], 'bad!! attr id of autoIncrement false');
    }

    public function tearDown()
    {
        $this->clear();
    }

    private function createTableOfInteger($autoIncrement = false)
    {
        return Builder::make()
            ->table('tests', function(Schema $schema) use($autoIncrement) {
                $schema->integer('id')
                    ->primary()
                    ->notNull()
                    ->autoIncrement($autoIncrement);
            });
    }

    private function clear()
    {
        $testSchemaFile = __DIR__ . '/fixtures/storage/tests.rtb';
        if (is_file($testSchemaFile)) {
            unlink($testSchemaFile);
        }
    }
}