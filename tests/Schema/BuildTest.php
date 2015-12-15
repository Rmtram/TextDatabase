<?php

namespace Rmtram\TextDatabase\UnitTest\Schema;

use Rmtram\TextDatabase\Connection;
use Rmtram\TextDatabase\Reader\Reader;
use Rmtram\TextDatabase\Schema\Builder;
use Rmtram\TextDatabase\Schema\Schema;

class BuildTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        Connection::setPath(__DIR__ . '/../fixtures/storage/');
    }

    public function testCreateTable()
    {
        $this->assertTrue($this->createTable());

    }

    public function testExistsSchema()
    {
        $this->createTable();
        $reader = new Reader();
        $schema = $reader->getSchema('tests');
        $this->assertNotEmpty($schema);
    }

    public function tearDown()
    {
        $this->clear();
    }

    private function createTable()
    {
        return Builder::make()
        ->table('tests', function(Schema $schema) {
            $schema->integer('id')
                ->primary()
                ->notNull()
                ->autoIncrement();
            $schema->string('title');
            $schema->text('description');
            $schema->date('birthday');
            $schema->dateTime('created_at');
        });
    }

    private function clear()
    {
        $testSchemaFile = __DIR__ . '/../fixtures/storage/tests.rtb';
        if (is_file($testSchemaFile)) {
            unlink($testSchemaFile);
        }
    }
}