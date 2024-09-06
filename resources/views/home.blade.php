<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - Blog Site</title>
    <!-- Optional: Add CSS here for styling the navbar and content -->
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        .navbar {
            background-color: #333;
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
            background-color: #575757;
            border-radius: 4px;
        }

        .container {
            max-width: 900px;
            margin: 20px auto;
            padding: 0 20px;
        }

        .blog-card {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-bottom: 20px;
            padding: 20px;
        }

        .blog-card h3 {
            margin-top: 0;
        }

        .blog-card p {
            margin: 10px 0;
        }

        .blog-card .read-more {
            text-decoration: none;
            color: #007bff;
        }

        .blog-card .read-more:hover {
            text-decoration: underline;
        }
    </style>
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

    <!-- Content -->
    <div class="container">
        <h1>Recent Blogs</h1>
        <p>Showing the latest three blog posts:</p>
        @foreach($recentBlogs as $blog)
            <div class="blog-card">
                <h3>{{ $blog->title }}</h3>
                <p>By: {{ $blog->author }} | {{ $blog->created_at->format('d M Y') }}</p>
                <p>{{ Str::limit($blog->body, 100) }}</p>
                <a href="{{ route('blog.show', $blog->id) }}" class="read-more">Read More</a>
            </div>
        @endforeach
    </div>
</body>
</html>
