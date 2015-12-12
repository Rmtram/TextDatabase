# TextDatabase
because the application has been creating does not work.

## Introduction

Create Table.

```
use Rmtram\TextDatabase\Schema\Builder;
use Rmtram\TextDatabase\Schema\Schema;

Builder::table('posts', function(Schema $schema) {
    $schema->integer('id')->primary();
    $schema->string('title');
    $schema->text('description')->null();
    $schema->dateTime('created');
    $schema->dateTime('updated')->null();
});
```