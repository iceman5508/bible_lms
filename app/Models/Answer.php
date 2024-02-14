<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    use HasFactory;

    protected $table = 'answers';
    protected $primaryKey = 'id';
    protected $fillable = [
        'exam_id',
        'question_id',
        'question_option_id',
        'take_exam_id',
        'multiple_choice_answer',
        'is_correct',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function exam()
    {
        return $this->belongsTo(Exam::class, 'exam_id');
    }

    public function question()
    {
        return $this->belongsTo(Question::class, 'question_id');
    }

    protected static function boot()
    {
        parent::boot();
        self::creating(function($model){
            $model->user_id =  auth()->id();
        });
    }

}
