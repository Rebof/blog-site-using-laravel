<?php

// app/Http/Controllers/BlogController.php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\Like;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;


class BlogController extends Controller
{
    // Show the home page with the three most recent blogs
    public function home()
    {
        $recentBlogs = Blog::where('status', 'published')
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get();

        return view('home', compact('recentBlogs'));
    }

    // Show all blogs in a paginated format
    public function index()
    {
        $blogs = Blog::where('status', 'published')
            ->select('id', 'title', 'user_id', 'created_at', 'body', 'author', 'category_id')
            ->orderBy('created_at', 'desc') // Order by creation date in descending order
            ->paginate(10);

        return view('blog', compact('blogs'));
    }


    // Show a single blog in detail
    public function show($id)
    {
        $blog = Blog::with('comments')->findOrFail($id);
        $userLike = $blog->likes->where('user_id', auth()->id())->first();

        return view('single-blog', compact('blog', 'userLike'));
    }

    // Admin: Show a form to create a new blog
    public function create()
    {
        $user = Auth::user();
        
        if ($user && $user->user_type == 'admin') {
            $categories = Category::all();
            return view('create', compact('categories'));
        }

        return redirect()->route('home');
    }

    // Admin: Store a newly created blog
    public function store(Request $request)
{
    $user = Auth::user();

    if ($user && $user->user_type == 'admin') {
        $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string',
            'author' => 'required',
            'category_id' => 'required|exists:categories,id',
            'status' => 'required|in:published,drafted',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Handle image upload
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imageName = time() . '.' . $request->image->extension();
            $request->image->storeAs('public/blog_images', $imageName);
            $imagePath = 'blog_images/' . $imageName; // Save relative path
        }

        // Create the blog post
        Blog::create([
            'user_id' => $user->id,
            'title' => $request->title,
            'author' => $request->author,
            'body' => $request->body,
            'category_id' => $request->category_id,
            'status' => $request->status,
            'image_path' => $imagePath, // Save relative path
        ]);

        return redirect()->route('admin.dashboard');
    }

    return redirect()->route('home');
}


    // Admin: Show a form to edit a blog
    public function edit($id)
    {
        $user = Auth::user();
        
        if ($user && $user->user_type == 'admin') {
            $blog = Blog::findOrFail($id);
            $categories = Category::all();

            return view('edit-blog', compact('blog', 'categories'));
        }

        return redirect()->route('home');
    }

    // Admin: Update a blog
    public function update(Request $request, $id)
{
    $user = Auth::user();

    if ($user && $user->user_type == 'admin') {
        $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string',
            'category_id' => 'required',
            'status' => 'required|in:published,drafted',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $blog = Blog::findOrFail($id);

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if it exists
            if ($blog->image_path) {
                Storage::disk('public')->delete($blog->image_path);
            }

            $imageName = time() . '.' . $request->image->extension();
            $request->image->storeAs('public/blog_images', $imageName);
            $blog->image_path = 'blog_images/' . $imageName; // Save relative path
        }

        // Update the blog post
        $blog->update([
            'title' => $request->title,
            'body' => $request->body,
            'category_id' => $request->category_id,
            'status' => $request->status,
            'image_path' => $blog->image_path, // Update image path if changed
        ]);

        return redirect()->route('admin.dashboard');
    }

    return redirect()->route('home');
}



    // Admin: Delete a blog
    public function destroy($id)
    {
        $user = Auth::user();
        
        if ($user && $user->user_type == 'admin') {
            $blog = Blog::findOrFail($id);
            $blog->delete();

            return redirect()->route('admin.dashboard');
        }

        return redirect()->route('home');
    }


        public function likeBlog(Request $request, $id)
    {
        $blog = Blog::find($id);

        // Check if the user has already liked/disliked the blog
        $existingLike = Like::where('user_id', auth()->id())
                            ->where('blog_id', $blog->id)
                            ->first();

        if ($existingLike) {
            // If already liked/disliked
            $existingLike->update([
                'is_like' => $request->is_like
            ]);
        } else {
            
            Like::create([
                'user_id' => auth()->id(),
                'blog_id' => $blog->id,
                'is_like' => $request->is_like
            ]);
        }

        // Return JSON response for AJAX
        return response()->json([
            'success' => true,
            'message' => 'Action completed successfully!'
        ]);
    }


// public function dislikeBlog(Request $request, $id)
// {
//     $blog = Blog::find($id);

//     $existingLike = Like::where('user_id', auth()->id())
//                         ->where('blog_id', $blog->id)
//                         ->first();

//     if ($existingLike) {
//         $existingLike->update([
//             'is_like' => false
//         ]);
//     } else {
//         Like::create([
//             'user_id' => auth()->id(),
//             'blog_id' => $blog->id,
//             'is_like' => false
//         ]);
//     }

//     return back();
// }

public function search(Request $request)
    {
        $search = $request->search;

        // Query to search in title, body, and related category
        $blogs = Blog::where(function($query) use ($search) {
            $query->where('title', 'like', "%$search%")
                  ->orWhere('body', 'like', "%$search%");
        })
        ->get();

        // Return search results to the view
        return view('blog', compact('blogs', 'search'));
    } 

    public function getSuggestions(Request $request)
{
    $search = $request->search;
    $suggestions = Blog::where('title', 'like', "%$search%")
        ->orWhere('body', 'like', "%$search%")
        ->select('title')
        ->limit(5)
        ->get();

    return response()->json($suggestions);
}

}
