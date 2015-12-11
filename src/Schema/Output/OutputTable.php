<?php

namespace Rmtram\TextDatabase\Schema\Output;

use Rmtram\TextDatabase\Schema\Schema;
use Rmtram\TextDatabase\Variable\Variable;

class OutputTable implements OutputInterface
{

    /**
     * table extension.
     * @var string
     */
    private $extension = 'rtb';

    /**
     * table name
     * @var string
     */
    private $table;

    /**
     * path
     * @var string
     */
    private $path;

    /**
     * @var Schema
     */
    private $schema;

    /**
     * construct.
     * @param string $table
     * @param string $path
     * @param Schema $schema
     */
    public function __construct($table, $path, Schema $schema)
    {
        $this->table = $table;
        $this->path = $path;
        $this->schema = $schema;
    }

    /**
     * @param bool $overwrite
     * @throws \RuntimeException
     */
    public function save($overwrite = false)
    {
        if (true === $this->exists() && false === $overwrite) {
            throw new \RuntimeException('exists table ' . $this->table);
        }
        $variables = $this->schema->__invoke();
        $properties = [];
        /** @var Variable $variable */
        foreach ($variables as $variable) {
            $properties[] = $variable();
        }
        $serialize = var_export($properties, true);
        $path = $this->getPath();
        if (!file_put_contents($path, $serialize)) {
            throw new \RuntimeException('save failed ' . $path);
        }
    }

    /**
     * exists table.
     * @return bool
     */
    private function exists()
    {
        return is_file($this->getPath());
    }

    /**
     * table path.
     * @return string
     */
    private function getPath()
    {
        return sprintf('%s%s.%s',
            $this->path, $this->table, $this->extension);
    }
}