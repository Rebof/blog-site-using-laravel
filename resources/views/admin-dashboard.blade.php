<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Blog Site</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            padding-top: 20px;
            padding-bottom: 20px;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
        }
        .card {
            margin-bottom: 20px;
        }
        .card-header {
            font-weight: bold;
            font-size: 1.25rem;
        }
        .card-body {
            padding: 15px;
        }
        .card-blogs {
            background-color: #f8f9fa; /* Light gray background for blogs */
            border-left: 5px solid #007bff; /* Blue left border for blogs */
        }
        .card-categories {
            background-color: #e9ecef; /* Slightly darker gray background for categories */
            border-left: 5px solid #28a745; /* Green left border for categories */
        }
        .btn {
            margin-right: 10px;
        }
        .logout-form {
            margin-top: 20px;
        }
        .user-info {
            margin-bottom: 20px;
        }
        .btn-warning {
            margin-right: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="text-center">Admin Dashboard</h1>

        <div class="user-info">
            <h2>Welcome, {{ Auth::user()->name }}!</h2>
            <p>Email: {{ Auth::user()->email }}</p>
        </div>

        <!-- Button to view all blog posts -->
        <div class="text-center mb-4">
            <a href="{{ route('blog') }}" class="btn btn-primary">View All Blog Posts</a>
        </div>

        <div class="card card-blogs">
            <div class="card-header">
                Manage Blogs
                <a href="{{ route('admin.blogs.create') }}" class="btn btn-primary float-right">Add New Blog</a>
            </div>
            <div class="card-body">
                @foreach($blogs as $blog)
                    <div class="card mb-3">
                        <div class="card-body">
                            <h5 class="card-title">{{ $blog->title }}</h5>
                            <h6 class="card-subtitle mb-2 text-muted">{{ $blog->author }} | {{ $blog->created_at->format('d M Y') }}</h6>
                            <p class="card-text">Status: {{ $blog->status }}</p>
                            <a href="{{ route('admin.blogs.edit', $blog->id) }}" class="btn btn-warning">Edit</a>
                            <form action="{{ route('admin.blogs.destroy', $blog->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Delete</button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="card card-categories">
            <div class="card-header">
                Manage Categories
                <a href="{{ route('admin.categories.create') }}" class="btn btn-primary float-right">Add New Category</a>
            </div>
            <div class="card-body">
                @foreach($categories as $category)
                    <div class="card mb-3">
                        <div class="card-body">
                            <p class="card-text"><strong>{{ $category->category_type }}:</strong> {{ $category->short_description }}</p>
                            <a href="{{ route('admin.categories.edit', $category->id) }}" class="btn btn-warning">Edit</a>
                            <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Delete</button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="logout-form text-center">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-secondary">Logout</button>
            </form>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
