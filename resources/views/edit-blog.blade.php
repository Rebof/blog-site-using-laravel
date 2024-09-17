<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Blog - Blog Site</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
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
            padding: 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .btn-primary {
            background-color: #e2d8d8;
            border-color: #333;
        }

        .btn-primary:hover {
            background-color: #575757;
            border-color: #575757;
        }

        .form-label {
            font-weight: bold;
        }

        .img-preview {
            max-width: 100%;
            height: auto;
            margin-top: 10px;
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
    <div class="container mt-5">
        <h1>Edit Blog</h1>
        <form action="{{ route('admin.blogs.update', $blog->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="title" class="form-label">Title:</label>
                <input type="text" class="form-control" name="title" id="title" value="{{ $blog->title }}" required>
            </div>
            <div class="mb-3">
                <label for="body" class="form-label">Content:</label>
                <textarea class="form-control" name="body" id="body" rows="5" required>{{ $blog->body }}</textarea>
            </div>
            <div class="mb-3">
                <label for="category_id" class="form-label">Category:</label>
                <select class="form-select" name="category_id" id="category_id" required>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ $blog->category_id == $category->id ? 'selected' : '' }}>
                            {{ $category->category_type }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label for="status" class="form-label">Status:</label>
                <select class="form-select" name="status" id="status" required>
                    <option value="published" {{ $blog->status == 'published' ? 'selected' : '' }}>Published</option>
                    <option value="drafted" {{ $blog->status == 'drafted' ? 'selected' : '' }}>Drafted</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="image" class="form-label">Image:</label>
                <input type="file" class="form-control" name="image" id="image" onchange="previewImage(event)">
                @if($blog->image_path)
                    <img src="{{ asset('storage/' . $blog->image_path) }}" alt="Current Image" class="img-preview mt-2" id="currentImage">
                @endif
                <img id="imgPreview" class="img-preview mt-2" style="display: none;">
            </div>
            <button type="submit" class="btn btn-primary">Update Blog</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>

    <script>
        function previewImage(event) {
            const input = event.target;
            const imgPreview = document.getElementById('imgPreview');
            const currentImage = document.getElementById('currentImage');

            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    imgPreview.src = e.target.result;
                    imgPreview.style.display = 'block';
                    if (currentImage) {
                        currentImage.style.display = 'none';
                    }
                };
                reader.readAsDataURL(input.files[0]);
            } else {
                imgPreview.style.display = 'none';
                if (currentImage) {
                    currentImage.style.display = 'block';
                }
            }
        }
    </script>
</body>
</html>
