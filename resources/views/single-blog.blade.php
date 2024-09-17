<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $blog->title }} - Blog Site</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4; /* Same background color as home page */
        }

        .navbar {
            background-color: #333; /* Navbar color */
            color: white;
            padding: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .navbar a {
            text-decoration: none;
            color: white;
            padding: 10px 15px;
        }

        .navbar a:hover {
            background-color: #575757; /* Navbar hover color */
            border-radius: 4px;
        }

        .container {
            max-width: 900px;
            margin: 20px auto;
            padding: 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            position: relative; /* Added to position the image */
        }

        h1 {
            margin-top: 0;
            color: #333;
        }

        p {
            color: #333;
        }

        .blog-image {
            float: right;
            margin-left: 20px;
            margin-bottom: 10px;
            max-width: 200px; /* Adjust size */
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .comments {
            margin-top: 20px;
        }

        .comment {
            margin-bottom: 10px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: #f9f9f9;
        }

        .comment .replies {
            margin-left: 20px;
            border-left: 2px solid #ddd;
            padding-left: 10px;
        }

        .admin-reply {
            color: #d9534f; /* Admin reply color */
        }

        form {
            margin-top: 10px;
        }

        textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        button {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            background-color: #007bff;
            color: white;
            cursor: pointer;
        }

        button:hover {
            background-color: #0056b3;
        }

        .like-dislike {
            margin-top: 20px;
        }

        .like-dislike button {
            margin-right: 10px;
        }
    </style>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

</head>
<body>
    <!-- Navbar -->
    <div class="navbar">
        <div>
            <a href="{{ route('home') }}">Home</a>
            <a href="{{ route('blog') }}">Blog</a>
        </div>
        <div>
            <a href="{{ route('login') }}">Login</a>
        </div>
    </div>

    <!-- Blog Content -->
    <div class="container">
        <h1>{{ $blog->title }}</h1>
        <p>By: {{ $blog->author }}</p>
        <p>Posted on: {{ $blog->created_at->format('d M Y H:i') }}</p>

        <!-- Blog Image -->
        @if($blog->image_path)
    <img src="{{ asset('storage/' . $blog->image_path) }}" alt="Blog Image" class="blog-image">
        @endif

        <div>
            {{ $blog->body }}
        </div>

        <!-- Like/Dislike Section -->
        <div class="like-dislike">
            @if(Auth::check())
                <button class="like-btn" data-blog-id="{{ $blog->id }}" {{ $userLike && $userLike->is_like ? 'disabled' : '' }}>
                    {{ $blog->likes->where('is_like', true)->count() }} 👍 Like
                </button>

                <button class="dislike-btn" data-blog-id="{{ $blog->id }}" {{ $userLike && !$userLike->is_like ? 'disabled' : '' }}>
                    {{ $blog->likes->where('is_like', false)->count() }} 👎 Dislike
                </button>
            @else
                <p>You need to be <a href="{{ route('login') }}">logged in</a> to like or dislike this post.</p>
            @endif
        </div>

        <!-- Comments Section -->
        <div class="comments">
            <h2>Comments:</h2>
            @foreach ($blog->comments->whereNull('parent_id') as $comment)
                <div class="comment">
                    <p><strong>{{ $comment->user->name }}</strong>: {{ $comment->comment }}</p>
                    @if ($comment->is_admin)
                        <p class="admin-reply">Admin Reply</p>
                    @endif

                    <!-- Display Replies -->
                    <div class="replies">
                        @foreach ($blog->comments->where('parent_id', $comment->id) as $reply)
                            <div class="comment">
                                <p><strong>{{ $reply->user->name }}</strong>: {{ $reply->comment }}</p>
                                @if ($reply->is_admin)
                                    <p class="admin-reply">Admin Reply</p>
                                @endif
                            </div>
                        @endforeach
                    </div>

                    <!-- Reply Form -->
                    @if(Auth::check() && Auth::user()->user_type === 'admin')
                        <form action="{{ route('comment.reply', ['blogId' => $blog->id, 'commentId' => $comment->id]) }}" method="POST">
                            @csrf
                            <textarea name="comment" rows="2" placeholder="Reply to this comment..."></textarea>
                            <button type="submit">Reply</button>
                        </form>
                    @endif
                </div>
            @endforeach

            <!-- Comment Form -->
            @if(Auth::check())
                <form action="{{ route('comment.store', $blog->id) }}" method="POST">
                    @csrf
                    <textarea name="comment" rows="4" placeholder="Add a comment..."></textarea>
                    <button type="submit">Submit</button>
                </form>
            @else
                <p>You need to be <a href="{{ route('login') }}">logged in</a> to comment.</p>
            @endif
        </div>
    </div>
</body>

<script>
    $(document).ready(function() {
        // Handle Like Button Click
        $('.like-btn').click(function() {
            var blogId = $(this).data('blog-id');

            $.ajax({
                url: '/blog/' + blogId + '/like',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    is_like: 1 // Like
                },
                success: function(response) {
                    // Update like/dislike count without reloading the page
                    location.reload();
                }
            });
        });

        // Handle Dislike Button Click
        $('.dislike-btn').click(function() {
            var blogId = $(this).data('blog-id');

            $.ajax({
                url: '/blog/' + blogId + '/like',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    is_like: 0 // Dislike
                },
                success: function(response) {
                    // Update like/dislike count without reloading the page
                    location.reload();
                }
            });
        });
    });
</script>

</html>
