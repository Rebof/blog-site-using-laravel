<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'blog_id',
        'comment',
        'is_admin',
        'parent_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }


    public function blog()
    {
        return $this->belongsTo(Blog::class);
    }


    // parent child cmt relationship concept
    public function parent()
    {
        return $this->belongsTo(Comment::class, 'parent_id');
    }

    public function replies()
    {
        return $this->hasMany(Comment::class, 'parent_id');
    }
}
