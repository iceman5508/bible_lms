<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlogComment extends Model
{
    use HasFactory;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function blog()
    {
        return $this->belongsTo(Blog::class);
    }

    public function blogCommentReplies()
    {
        return $this->hasMany(BlogComment::class, 'parent_id', 'id');
    }

    public function scopeActive()
    {
        return $this->where('status', 1);
    }
}
