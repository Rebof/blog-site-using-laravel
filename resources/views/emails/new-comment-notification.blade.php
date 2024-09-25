<!DOCTYPE html>
<html>
<head>
    <title>New Comment Notification</title>
</head>
<body>
    <h1>New Comment on Your Blog Post</h1>
    <p><strong>Blog:</strong> {{ $blog->title }}</p>
    <p><strong>Comment:</strong> {{ $comment->comment }}</p>
    <p><strong>By:</strong> {{ $comment->user->name }}</p>
</body>
</html>
