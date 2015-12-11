<?php

namespace Rmtram\TextDatabase\Schema\Output;

use Rmtram\TextDatabase\Schema\Schema;

interface OutputInterface
{
    /**
     * construct.
     * @param mixed $expression
     * @param string $path
     * @param Schema $schema
     */
    public function __construct($expression, $path, Schema $schema);
}