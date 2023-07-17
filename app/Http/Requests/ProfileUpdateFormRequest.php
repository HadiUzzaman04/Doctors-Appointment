<?php

namespace App\Http\Requests;

use App\Http\Requests\FormRequest;


class ProfileUpdateFormRequest extends FormRequest
{
    protected $rules = [];
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
        $this->rules['name']                  = ['required', 'string'];
        $this->rules['gender']                = ['required'];
        $this->rules['phone']                 = ['required', 'string', 'max:15', 'unique:users,phone,'.auth()->user()->id];
        return $this->rules;
    }
}
