<?php

namespace App\Mail;

use App\Models\Comment;
use App\Models\Blog;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewCommentNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $comment;
    public $blog;

    /**
     * Create a new message instance.
     */
    public function __construct(Comment $comment, Blog $blog)
    {
        $this->comment = $comment;
        $this->blog = $blog;
    }

    /**
     * Build the message.
     */
    public function build()
{
    return $this->subject('New Comment on Your Blog Post')
                ->view('emails.new-comment-notification')  // Update this to the correct view path
                ->with([
                    'comment' => $this->comment,
                    'blog' => $this->blog,
                ]);
}
}
