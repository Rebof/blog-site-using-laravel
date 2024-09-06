<?php

// app/Http/Controllers/CategoryController.php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\BlogCategory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class CategoryController extends Controller
{

    // Admin: Show a form to create a new category
    public function create()
    {
        $user = Auth::user();
        
        if ($user && $user->user_type == 'admin') {
            return view('create-category');
        }

        return redirect()->route('home');
    }

        
    

    // Admin: Store a newly created category
    public function store(Request $request)
    {
        // Ensure the user is logged in and is an admin
        $user = Auth::user();
        
        if ($user && $user->user_type == 'admin') {
            // Validate the request data
            $request->validate([
                'category_type' => 'required',
                'short_description' => 'required',
            ]);

            // Create a new category
            Category::create([
                'category_type' => $request->category_type,
                'short_description' => $request->short_description,
            ]);

            // Redirect to the categories index or any other appropriate route
            return redirect()->route('admin.dashboard');
        }

        // Redirect to home if the user is not an admin
        return redirect()->route('home');
    }


    // Admin: Show a form to edit a category
    public function edit($id)
    {
        $category = Category::findOrFail($id);

        return view('edit-category', compact('category'));
    }

    // Admin: Update a category
    public function update(Request $request, $id)
    {
        $request->validate([
            'type' => 'required|string|max:255',
            'short_description' => 'nullable|string|max:255',
        ]);

        $category = Category::findOrFail($id);
        $category->update([
            'type' => $request->type,
            'short_description' => $request->short_description,
        ]);

        return redirect()->route('admin.dashboard');
    }

    // Admin: Delete a category
    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();

        return redirect()->route('admin.dashboard');
    }
}