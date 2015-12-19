<?php

namespace Rmtram\TextDatabase\UnitTest\Schema;

use Respect\Validation\Exceptions\AllOfException;
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

    public function testTableNameByBackQuote()
    {
        $this->assertByTableName('`');
    }

    public function testTableNameByBackSlash()
    {
        $this->assertByTableName('\\');
    }

    public function testTableNameByHyphen()
    {
        $this->assertByTableName('-');
    }

    public function testTableNameBySharp()
    {
        $this->assertByTableName('#');
    }

    public function testTableNameByMultiByte()
    {
        $this->assertByTableName('あいうえお');
    }

    public function testTableNameByTilde()
    {
        $this->assertByTableName('~');
    }

    public function testTableNameBySlash()
    {
        $this->assertByTableName('/');
    }

    public function testTableNameByAtMark()
    {
        $this->assertByTableName('@');
    }

    public function testTableNameHtmlTag()
    {
        $this->assertByTableName('<br/>');
    }

    public function testTableNameByScriptTag()
    {
        $this->assertByTableName('<script>alert(1)</script>');
    }

    public function testTableNameByArray()
    {
        $this->assertByTableName(['a']);
    }

    public function testTableNameByInteger()
    {
        $this->assertByTableName(0);
    }

    public function testTableNameByFloat()
    {
        $this->assertByTableName(123.456);
    }

    public function testTableNameByObject()
    {
        $this->assertByTableName(new \stdClass());
    }

    public function testFieldNameByBackQuote()
    {
        $this->assertByFieldName('`');
    }

    public function testFieldNameByBackSlash()
    {
       $this->assertByFieldName('\\');
    }

    public function testFieldNameByHyphen()
    {
        $this->assertByFieldName('-');
    }

    public function testFieldNameBySharp()
    {
        $this->assertByFieldName('#');
    }

    public function testFieldNameByMultiByte()
    {
        $this->assertByFieldName('あいうえお');
    }

    public function testFieldNameByTilde()
    {
        $this->assertByFieldName('~');
    }

    public function testFieldNameBySlash()
    {
        $this->assertByFieldName('/');
    }

    public function testFieldNameByAtMark()
    {
        $this->assertByFieldName('@');
    }

    public function testFieldNameHtmlTag()
    {
        $this->assertByFieldName('<br/>');
    }

    public function testFieldNameByScriptTag()
    {
        $this->assertByFieldName('<script>alert(1)</script>');
    }

    public function testFieldNameByArray()
    {
        $this->assertByFieldName(['a']);
    }

    public function testFieldNameByInteger()
    {
        $this->assertByFieldName(0);
    }

    public function testFieldNameByFloat()
    {
        $this->assertByFieldName(123.456);
    }

    public function testFieldNameByObject()
    {
        $this->assertByFieldName(new \stdClass());
    }

    public function testExistsFieldTable()
    {
        try {
            Builder::make()
                ->table('tests', function(Schema $schema) {
                    $schema->string('title');
                    $schema->string('title');
                });
            $this->fail('bad exists fields...');
        }
        catch (\Exception $e) {
            $this->assertEquals(
                get_class($e), \InvalidArgumentException::class);
        }
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

    private function assertByTableName($tableName)
    {
        try {
            Builder::make()
                ->table($tableName, function(Schema $schema) {
                    $schema->string('dummy');
                });
            $this->fail('invalid table name...');
        }
        catch (\Exception $e) {
            $this->assertEquals(get_class($e), AllOfException::class);
        }
    }

    private function assertByFieldName($fieldName)
    {
        try {
            Builder::make()
                ->table('tests', function(Schema $schema) use($fieldName) {
                    $schema->string($fieldName);
                });
            $this->fail('invalid field name...');
        }
        catch (\Exception $e) {
            $this->assertTrue(true);
        }
    }

    private function clear()
    {
        $testSchemaFile = __DIR__ . '/../fixtures/storage/tests.rtb';
        if (is_file($testSchemaFile)) {
            unlink($testSchemaFile);
        }
    }
}