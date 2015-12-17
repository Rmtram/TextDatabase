<?php require __DIR__ . '/../bootstrap.php' ?>
<?php require __DIR__ . '/../Application/Action/Index.php' ?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Index</title>
</head>
<body>
<table border="1" style="width:80%;margin:0 10%;text-indent: 1em">
    <thead>
    <tr>
        <th>User Id</th>
        <th>User Name</th>
        <th>Posts</th>
        <th>Created</th>
        <th>Updated</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach($users as $user): ?>
    <tr>
        <td><?= htmlspecialchars($user->id) ?></td>
        <td><?= htmlspecialchars($user->name) ?></td>
        <td>
            <?php $posts = $user->posts ?>
            <?php if ($posts): ?>
                <ul>
                    <?php foreach($posts as $post): ?>
                        <li><?= htmlspecialchars($post->title) ?></li>
                        <?php if ($comments = $post->comments): ?>
                            <ul>
                            <?php foreach($comments as $comment): ?>
                                <li><?= htmlspecialchars($comment->comment) ?></li>
                            <?php endforeach; ?>
                            </ul>
                        <?php endif ?>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </td>
        <td><?= htmlspecialchars($user->created_at) ?></td>
        <td><?= htmlspecialchars($user->updated_at) ?></td>
    </tr>
    <?php endforeach ?>
    </tbody>
</table>
</body>
</html>