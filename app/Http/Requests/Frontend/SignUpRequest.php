<?php

namespace App\Http\Requests\Frontend;

use Illuminate\Foundation\Http\FormRequest;

class SignUpRequest extends FormRequest
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
            'first_name' => ['required', 'string','min:2', 'max:100'],
            'last_name' => ['required', 'string','min:2', 'max:100'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:6'],
        ];

        if(!is_null(request()->input('mobile_number')) || !is_null(request()->input('area_code'))){
            $rules['area_code'] = 'required';
            $rules['mobile_number'] = 'bail|numeric|unique:users';
        }

        return $rules;
    }
}
