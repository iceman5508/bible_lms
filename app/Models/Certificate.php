<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Certificate extends Model
{
    use HasFactory;

    protected $table = 'certificates';
    protected $primaryKey = 'id';
    protected $appends = ['image_url', 'role_1_signature_url', 'path_url'];

    protected $fillable = [
        'certificate_number',
        'image',
        'show_number',
        'number_x_position',
        'number_y_position',
        'number_font_size',
        'number_font_color',
        'title',
        'title_x_position',
        'title_y_position',
        'title_font_size',
        'title_font_color',
        'show_date',
        'date_x_position',
        'date_y_position',
        'date_font_size',
        'date_font_color',
        'show_student_name',
        'student_name_x_position',
        'student_name_y_position',
        'student_name_font_size',
        'student_name_font_color',
        'body',
        'body_max_length',
        'body_x_position',
        'body_y_position',
        'body_font_size',
        'body_font_color',
        'role_1_show',
        'role_1_title',
        'role_1_x_position',
        'role_1_y_position',
        'role_1_font_size',
        'role_1_font_color',
        'role_2_show',
        'role_2_title',
        'role_2_x_position',
        'role_2_y_position',
        'role_2_font_size',
        'role_2_font_color',
        'status',
    ];

    public function getImageUrlAttribute()
    {
        if ($this->image) {
            return asset($this->image);
        } else {
            return asset('uploads/default/no-image-found.png');
        }
    }
    
    public function getPathUrlAttribute()
    {
        if ($this->path) {
            return asset($this->path);
        } else {
            return asset('uploads/default/no-image-found.png');
        }
    }
   
    public function getRole1SignatureUrlAttribute()
    {
        if ($this->role_1_signature) {
            return asset($this->role_1_signature);
        } else {
            return asset('uploads/default/course.jpg');
        }
    }

}
