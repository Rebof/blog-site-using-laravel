<?php

namespace App\Http\Controllers;
use App\Mail\NewCommentNotification;

use Illuminate\Http\Request;
use App\Models\Blog;
use App\Models\User;
use App\Models\Comment;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function store(Request $request, $blogId)
    {
        // Ensure user is logged in
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'You need to be logged in to comment.');
        }

        // Validate comment input
        $request->validate([
            'comment' => 'required|string|max:1000',
        ]);

        // Determine if the user is an admin
        $isAdmin = Auth::user()->user_type === 'admin';

        // Create the comment
        $comment = Comment::create([
            'user_id' => Auth::id(),
            'blog_id' => $blogId,
            'comment' => $request->input('comment'),
            'is_admin' => $isAdmin, 
        ]);

        
        $blog = Blog::findOrFail($blogId); 
        $blogAuthor = $blog->user;  
        Mail::to($blogAuthor->email)->send(new NewCommentNotification($comment, $blog));

        
        return redirect()->route('blog.show', $blogId)->with('success', 'Comment posted and notification sent successfully.');
    }


    public function reply(Request $request, $blogId, $commentId)
{
    
    if (Auth::user()->user_type !== 'admin') {
        return redirect()->route('blog.show', $blogId);
    }

    
    $request->validate([
        'comment' => 'required|string|max:1000',
    ]);

    
    Comment::create([
        'user_id' => Auth::id(),
        'blog_id' => $blogId,
        'comment' => $request->input('comment'),
        'is_admin' => true, 
        'parent_id' => $commentId, 
    ]);

    return redirect()->route('blog.show', $blogId);
}

    // public function show($blogId)
    // {
    //     $blog = Blog::with('comments')->findOrFail($blogId);
    //     return view('single-blog', compact('blog'));
    // }

}
