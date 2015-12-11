<?php

use Rmtram\TextDatabase\Schema\Builder;
use Rmtram\TextDatabase\Schema\Schema;

require __DIR__ . '/vendor/autoload.php';

$path = __DIR__ . DIRECTORY_SEPARATOR . 'tdb' . DIRECTORY_SEPARATOR;

Builder::make($path)
    ->setOverwrite(true)
    ->table('users', function(Schema $schema) {
    $schema->integer('id')->primary();
    $schema->date('w');
    $schema->string('title');
    $schema->text('description');
});