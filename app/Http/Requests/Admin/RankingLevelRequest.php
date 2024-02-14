<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class RankingLevelRequest extends FormRequest
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
        $id = $this->badge ? $this->badge->id : null;
        $rules = [
            'name'  => 'bail|required|max:255|unique:ranking_levels,name,' . $id . ',id,type,' . $this->request->get('type'),
            'from' => 'bail|required|min:0',
            'to' => 'bail|required|min:0',
            'description' => 'max:255',
            'serial_no' => 'nullable',
            'earning' => 'nullable',
            'student' => 'nullable',
            'badge_image' => 'bail|required|mimes:png,jpg|file|dimensions:width=30,height=30|max:100'
        ];

        if($id){
            $rules['badge_image'] = 'bail|nullable|mimes:png,jpg|file|dimensions:width=30,height=30|max:100';
        }
        else{
            $rules['badge_image'] = 'bail|required|mimes:png,jpg|file|dimensions:width=30,height=30|max:100';
        }

        return $rules;
    }
}
