<?php

namespace App\Http\Requests\Instructor;

use Illuminate\Foundation\Http\FormRequest;

class CourseUpdateCategoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'category_id' => ['required'],
            'subcategory_id' => ['required'],
            'price' => ['required', 'numeric'],
            'course_language_id' => ['required'],
            'difficulty_level_id' => ['required'],
            'image' => 'mimes:jpeg,png,jpg|file|max:2048',
        ];
    }
}
