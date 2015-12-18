# TextDatabase

[![Build Status](https://travis-ci.org/Rmtram/TextDatabase.svg)](https://travis-ci.org/Rmtram/TextDatabase)

**Sorry!! the application has been creating does not work.**

## Introduction

Create Table.

```php
use Rmtram\TextDatabase\Schema\Builder;
use Rmtram\TextDatabase\Schema\Schema;

require __DIR__ . '/vendor/autoload.php';

Rmtram\TextDatabase\Connection::setPath('path/to/');

Builder::make()
    ->table('users', function(Schema $schema) {
    $schema->integer('id')->autoIncrement()->primary();
    $schema->string('name')->notNull();
    $schema->dateTime('created_at');
    $schema->dateTime('updated_at');
});

Builder::make()
    ->table('posts', function(Schema $schema) {
    $schema->integer('id')->autoIncrement()->primary();
    $schema->string('title')->notNull();
    $schema->string('description')->notNull();
    $schema->integer('user_id');
    $schema->dateTime('created_at');
    $schema->dateTime('updated_at');
});

Builder::make()
    ->table('comments', function(Schema $schema) {
    $schema->integer('id')->autoIncrement()->primary();
    $schema->text('comment')->notNull();
    $schema->integer('post_id');
    $schema->dateTime('created_at');
    $schema->dateTime('updated_at');
});
```

Create EntityManager

```php

class UserEntityManager extends BaseEntityManager
{
    /**
     * @var string
     */
    protected $table = 'users';

    /**
     * @var string
     */
    protected $entity = User::class;

    /**
     * @var array
     */
    protected $hasMany = [
        'posts' => Post::class
    ];

}

class PostEntityManager extends BaseEntityManager
{
    /**
     * @var string
     */
    protected $table  = 'posts';

    /**
     * @var string
     */
    protected $entity = Post::class;

    /**
     * @var array
     */
    protected $belongsTo = [
        'user' => User::class
    ];

    /**
     * @var array
     */
    protected $hasMany = [
        'comments' => Comment::class
    ];
}

class CommentEntityManager extends BaseEntityManager
{
    /**
     * @var string
     */
    protected $table  = 'comments';

    /**
     * @var string
     */
    protected $entity = Comment::class;

    /**
     * @var array
     */
    protected $belongsTo = [
        'post' => Post::class
    ];
}

```

Create Entity

```php

class User extends BaseEntity
{
    /**
     * @var int
     */
    public $id;

    /**
     * @var string
     */
    public $name;

    /**
     * @var \DateTime|string
     */
    public $created_at;

    /**
     * @var \DateTime|string
     */
    public $updated_at;

    /**
     * @var string
     */
    protected static $entityManager = UserEntityManager::class;
}

class Post extends BaseEntity
{
    /**
     * @var int
     */
    public $id;

    /**
     * @var string
     */
    public $title;

    /**
     * @var string
     */
    public $description;

    /**
     * @var int
     */
    public $user_id;

    /**
     * @var \DateTime|string
     */
    public $created_at;

    /**
     * @var \DateTime|string
     */
    public $updated_at;

    /**
     * @var string
     */
    protected static $entityManager = PostEntityManager::class;
}

class Comment extends BaseEntity
{
    /**
     * @var int
     */
    public $id;

    /**
     * @var string
     */
    public $comment;

    /**
     * @var int
     */
    public $post_id;

    /**
     * @var string|\DateTime
     */
    public $created_at;

    /**
     * @var string|\DateTime
     */
    public $updated_at;

    /**
     * @var string
     */
    protected static $entityManager = CommentEntityManager::class;
}

```

### Create, Read, Update, Delete

Create and Update.

```php

// Create
$user = new User();
$user->name = 'NickName'
$user->created_at = new DateTime();
$user->updated_at = new DateTime();
UserEntityManager::save($user);

// Update
$user = new User();
$user->id = 1; // primary key.
$user->name = 'NickName'
$user->created_at = new DateTime();
$user->updated_at = new DateTime();
UserEntityManager::save($user);

```

Read.

```php

$users = UserEntityManager::find()->all();
foreach ($users as $user) {
    echo $user->id;
    echo $user->name;
}

// sort
$users = UserEntityManager::find()
    ->order(['name' => 'asc'])
    ->all();

// relation
$user = UserEntityManager::find()->first();
foreach ($user->posts as $post) {
    echo $post->title;
    foreach ($post->comments as $comment) {
        echo $comment->comment;
        echo $comment->user->name;
    }
}
```

Delete.

```php

// all
UserEntityManager::delete();

// where
$userRepository = new UserRepository();
UserEntityManager::delete(['id' => 1])

// entity
$user = UserEntityManager::find()->first();
UserEntityManager::delete($user);

```