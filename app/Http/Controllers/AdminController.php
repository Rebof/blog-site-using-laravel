<?php


namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    // Show the admin dashboard
    public function index()
    {
        // Ensure the user is an admin
        if (Auth::check() && Auth::user()->user_type === 'admin') {
         // Fetch blogs and categories for the admin dashboard
         $blogs = Blog::all();
         $categories = Category::all();

         // Pass the blogs and categories data to the view
         return view('admin-dashboard', compact('blogs', 'categories'));
     }

        // Redirect to login if not an admin
        return redirect()->route('login');
    }
}