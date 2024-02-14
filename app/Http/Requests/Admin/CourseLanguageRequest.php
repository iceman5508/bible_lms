<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class CourseLanguageRequest extends FormRequest
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
        $rules = [
            'name' => ['required', 'string', 'max:255'],
        ];

//        if ($this->getMethod() == 'PATCH') {
//            $rules['name'] = ['required', Rule::unique('tags', 'name')->ignore($this->route('tag.update'))];
//        }
        return $rules;
    }
}
