<?php

namespace Rmtram\TextDatabase\Schema;
use Rmtram\TextDatabase\Output\SchemaWriter;

/**
 * Class Builder
 * @package Rmtram\TextDatabase\Schema
 */
class Builder
{

    /**
     * @var bool
     */
    private $overwrite = false;

    /**
     * @var string
     */
    private $schemaClassName = Schema::class;

    /**
     * constructor.
     */
    protected function __construct() {}

    /**
     * @return static
     */
    public static function make()
    {
        return new static();
    }

    /**
     * @param $schemaClassName
     * @return $this
     */
    public function setSchemaClassName($schemaClassName)
    {
        if (!is_a($schemaClassName, Schema::class)) {
            throw new \RuntimeException(
                'bad invalid class ' .  $schemaClassName);
        }
        $this->schemaClassName = $schemaClassName;
        return $this;
    }

    /**
     * @param bool $bool
     * @return $this
     */
    public function setOverwrite($bool)
    {
        if (is_bool($bool)) {
            $this->overwrite = $bool;
        }
        return $this;
    }

    /**
     * @param string $table
     * @param \Closure $closure
     */
    public function table($table, \Closure $closure)
    {
        /** @var Schema $schema */
        $schema = new $this->schemaClassName;
        $closure($schema);
        $writer = new SchemaWriter($table, $schema);
        $writer->write($this->overwrite);
    }

}