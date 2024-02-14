<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Discussion extends Model
{
    use HasFactory;

    protected $fillable = [
        'view'
    ];

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    public function scopeNotView($query)
    {
        return $query->whereView(2);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    public function replies()
    {
        return $this->hasMany(Discussion::class, 'parent_id');
    }
}
