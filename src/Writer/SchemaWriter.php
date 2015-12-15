<?php

namespace Rmtram\TextDatabase\Writer;

use Rmtram\TextDatabase\Connection;
use Rmtram\TextDatabase\Schema\Schema;

class SchemaWriter extends AbstractWriter
{

    /**
     * table name
     * @var string
     */
    private $table;

    /**
     * @var Schema
     */
    private $schema;

    /**
     * construct.
     * @param string $table
     * @param Schema $schema
     */
    public function __construct($table, Schema $schema)
    {
        $this->table = $table;
        $this->schema = $schema;
    }

    /**
     * table path.
     * @return string
     */
    public function getPath()
    {
        return sprintf('%s%s.%s',
            Connection::getPath(),
            $this->table,
            Connection::getSchemaExtension());
    }

    /**
     * export data.
     * @return string
     */
    protected function export()
    {
        return serialize($this->schema->__invoke());
    }
}