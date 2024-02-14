<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class CouponRequest extends FormRequest
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
            'coupon_code_name' => 'required|unique:coupons,coupon_code_name',
            'coupon_type' => ['required'],
            'percentage' => ['required'],
            'minimum_amount' => ['required'],
            'start_date' => ['required'],
            'end_date' => ['required'],
        ];
    }
}
