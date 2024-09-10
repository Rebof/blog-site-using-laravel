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
        
        if (Auth::check() && Auth::user()->user_type === 'admin') {
         
         $user = Auth::user();
         $blogs = Blog::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);
         $categories = Category::all();

         
         return view('admin-dashboard', compact('blogs', 'categories'));
     }

        return redirect()->route('login');
    }
}