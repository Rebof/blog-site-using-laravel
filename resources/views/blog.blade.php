<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blogs - Blog Site</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            color: #333;
        }

        .container {
            max-width: 900px;
            margin: 20px auto;
            padding: 0 20px;
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

        /* Search form styling */
        .search-wrapper {
            display: flex;
            justify-content: center;
            margin-bottom: 30px;
            position: relative;
        }

        .search-wrapper input {
            width: 100%;
            max-width: 600px;
            padding: 12px 20px;
            border-radius: 25px;
            border: 1px solid #ddd;
            outline: none;
            font-size: 16px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            transition: border-color 0.3s;
            box-sizing: border-box; /* Ensures padding and border are included in width */
        }

        .search-wrapper input:focus {
            border-color: #007bff;
        }

        .search-wrapper button {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            padding: 10px 20px;
            border: none;
            background-color: #007bff;
            color: white;
            border-radius: 20px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
            z-index: 1; /* Ensure button is above input */
        }

        .search-wrapper button:hover {
            background-color: #0056b3;
        }

        /* Suggestions dropdown */
        #suggestionsList {
            position: absolute;
            background-color: white;
            border: 1px solid #ddd;
            border-radius: 4px;
            z-index: 1000; /* High z-index to appear above other content */
            max-height: 200px;
            overflow-y: auto;
            width: 100%;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-top: 45px; /* Space for the suggestion box to be visible below the search input */
        }

        #suggestionsList li {
            padding: 10px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        #suggestionsList li:hover {
            background-color: #f0f0f0;
        }

        /* Blog list styling */
        .blog-card {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            padding: 20px;
            transition: box-shadow 0.3s, transform 0.3s;
        }

        .blog-card:hover {
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
            transform: translateY(-5px);
        }

        .blog-card h3 {
            margin-top: 0;
            font-size: 24px;
            color: #333;
        }

        .blog-card p {
            margin: 10px 0;
            color: #555;
        }

        .blog-card .read-more {
            text-decoration: none;
            color: #007bff;
            font-weight: bold;
        }

        .blog-card .read-more:hover {
            text-decoration: underline;
        }

        .text-center {
            text-align: center;
        }

        /* Loading spinner */
        .loading {
            border: 4px solid #f3f3f3;
            border-radius: 50%;
            border-top: 4px solid #007bff;
            width: 24px;
            height: 24px;
            animation: spin 2s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Responsive */
        @media (max-width: 768px) {
            .search-wrapper input {
                width: 100%;
                max-width: none;
            }

            .blog-card {
                margin: 10px 0;
            }
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

    <div class="container">
        <!-- Search Form -->
        <div class="search-wrapper">
            <input id="search-input" type="text" name="search" placeholder="Search..." value="{{ isset($search) ? $search : '' }}" autocomplete="off">
            <button type="submit" onclick="submitSearch()">Search</button>
            <div id="suggestionsList" style="display: none;"></div>
        </div>

        <!-- Blog List -->
        <h1 class="text-center">All Blogs</h1>
        @forelse($blogs as $blog)
            <div class="blog-card">
                <h3>{{ $blog->title }}</h3>
                <p>By: {{ $blog->author }} | {{ $blog->created_at->format('d M Y') }}</p>
                <p>Category: {{ $blog->category ? $blog->category->category_type : 'No Category' }}</p>
                <p>{{ Str::limit($blog->body, 150) }}</p>
                <a href="{{ route('blog.show', $blog->id) }}" class="read-more">Read More</a>
            </div>
        @empty
            <p class="text-center">No blogs found matching your search.</p>
        @endforelse
    </div>

    <script>
        let timer;

        // Function to submit search
        function submitSearch() {
            const searchInput = document.getElementById('search-input').value;
            if (searchInput) {
                window.location.href = `{{ route('search') }}?search=${searchInput}`;
            }
        }

        // Function to show search suggestions
        document.getElementById('search-input').addEventListener('input', function() {
            const searchValue = this.value;

            if (searchValue.length > 1) {
                clearTimeout(timer);
                timer = setTimeout(() => {
                    fetch(`/api/blogs/suggestions?search=${searchValue}`)
                        .then(response => response.json())
                        .then(data => {
                            let suggestionsList = document.getElementById('suggestionsList');
                            suggestionsList.innerHTML = '';

                            if (data.length > 0) {
                                suggestionsList.style.display = 'block';
                                data.forEach(item => {
                                    let suggestion = document.createElement('li');
                                    suggestion.textContent = item.title;
                                    suggestion.onclick = () => {
                                        document.getElementById('search-input').value = item.title;
                                        suggestionsList.style.display = 'none';
                                    };
                                    suggestionsList.appendChild(suggestion);
                                });
                            } else {
                                suggestionsList.style.display = 'none';
                            }
                        });
                }, 300); // Add a delay to avoid too many requests
            }
        });
    </script>
</body>
</html>
