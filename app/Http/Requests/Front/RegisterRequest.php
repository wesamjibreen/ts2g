<?php

namespace App\Http\Requests\Front;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
            'email'=>'required|string|email|max:100|unique:users',
            'gender_id'=>'required',
            'day'=>'required',
            'month'=>'required',
            'year'=>'required',
            'country'=>'required',
            'city'=>'required',
            'password'=>'required|string|min:6',
        ];
    }
}
