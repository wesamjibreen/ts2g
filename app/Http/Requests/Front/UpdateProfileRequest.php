<?php

namespace App\Http\Requests\Front;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
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
            'f_name'=>'required|string|max:100',
            'l_name'=>'required|string|max:100',
            'email' => 'required|string|max:255|unique:users,email,'.auth()->user()->id.',id,deleted_at,NULL',
            'gender_id'=>'required',
            'day'=>'required',
            'month'=>'required',
            'year'=>'required',
            'country'=>'required',
            'city'=>'required',
        ];
    }
}
