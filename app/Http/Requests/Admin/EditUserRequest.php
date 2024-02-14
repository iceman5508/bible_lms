<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class EditUserRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => ['required', 'max:120'],
            'email' => ['required', 'email', 'unique:users,email,'.$this->id],
            'area_code' => 'required',
            'phone_number' => 'bail|numeric|unique:users,mobile_number,'.$this->id,
            'address' => ['required'],
            'role_name' => ['required']
        ];
    }
}
