<?php

namespace Rmtram\TextDatabase\Schema;
use Rmtram\TextDatabase\Schema\Output\OutputTable;

/**
 * Class Builder
 * @package Rmtram\TextDatabase\Schema
 */
class Builder
{

    /**
     * @var string
     */
    private $path;

    /**
     * @var bool
     */
    private $overwrite = false;

    /**
     * @var string
     */
    private $schemaClassName = Schema::class;

    /**
     * @param string $path
     */
    protected function __construct($path)
    {
        $this->path = $path;
    }

    /**
     * @param string $path
     * @return static
     */
    public static function make($path)
    {
        return new static($path);
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
        $output = new OutputTable($table, $this->path, $schema);
        $output->save($this->overwrite);
    }

}