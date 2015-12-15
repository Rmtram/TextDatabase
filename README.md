# TextDatabase
because the application has been creating does not work.

## Introduction

Create Table.

```php
use Rmtram\TextDatabase\Schema\Builder;
use Rmtram\TextDatabase\Schema\Schema;

require __DIR__ . '/vendor/autoload.php';

Rmtram\TextDatabase\Connection::setPath('path/to/');

Builder::make()
    ->setOverwrite(true)
    ->table('users', function(Schema $schema) {
    $schema->integer('id')->autoIncrement()->primary();
    $schema->string('name')->notNull();
});

Builder::make()
    ->setOverwrite(true)
    ->table('books', function(Schema $schema) {
    $schema->integer('id')->autoIncrement()->primary();
    $schema->string('isbn')->unique()->notNull();
    $schema->string('title')->notNull();
    $schema->string('description')->notNull();
    $schema->integer('user_id');
});

Builder::make()
    ->setOverwrite(true)
    ->table('book_comments', function(Schema $schema) {
    $schema->integer('id')->autoIncrement()->primary();
    $schema->text('body')->notNull();
    $schema->integer('book_id');
});
```

Create Repository

```php

class UserRepository extends Rmtram\TextDatabase\Repository\BaseRepository {
    protected $table = 'users';
    protected $entityClass = User::class;
    protected $hasMany = [
        'books'    => Book::class,
        'comments' => BookComment::class
    ];
}

class BookRepository extends Rmtram\TextDatabase\Repository\BaseRepository {
    protected $table = 'books';
    protected $entityClass = Book::class;
    protected $belongsTo = [
        'user' => User::class
    ];
    protected $hasMany = [
        'comments' => BookComment::class
    ];
}

class BookCommentRepository extends Rmtram\TextDatabase\Repository\BaseRepository {
    protected $table = 'book_comments';
    protected $entityClass = BookComment::class;
    protected $belongsTo = [
        'book' => Book::class,
        'user' => User::class
    ];
}


```

Create Entity

```php

class User extends Rmtram\TextDatabase\Entity\BaseEntity 
{
    public $id;
    public $title;
    
    protected $repository = UserRepository::class;
}

class Book extends Rmtram\TextDatabase\Entity\BaseEntity {
    public $id;
    public $isbn;
    public $title;
    public $description;
    public $user_id;
    
    protected $repository = BookRepository::class;
}

class BookComment extends Rmtram\TextDatabase\Entity\BaseEntity {
    public $id;
    public $body;
    public $book_id;
    public $user_id;
    protected $repository = BookCommentRepository::class;
}

```

Crud

```php
// Create and Update
$userRepository = new UserRepository();
$user = new User();
$user->id = 1;
$user->title = 'NickName'
$userRepository->save($user);

// Read
$userRepository = new UserRepository();
$users = $userRepository->find()->all();
foreach ($users as $user) {
    echo $user->name;
}
// Read association
$user = $userRepository->find()->first();
foreach ($user->books as $book) {
    echo $book->title;
    foreach ($book->comments as $comment) {
        echo $comment->body;
        echo $comment->user->name;
    }
}
```