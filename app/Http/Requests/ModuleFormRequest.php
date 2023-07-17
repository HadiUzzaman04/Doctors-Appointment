<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use App\Http\Requests\FormRequest;

class ModuleFormRequest extends FormRequest
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

        $rules['type']                = ['required','integer'];
        if(request()->type == 1){
            $rules['divider_title']   = ['required','string'];
        }else{
            $rules['module_name']     = ['required','string'];
            if(request()->update_id){
                $rules['url']         = ['nullable','string',Rule::unique('modules','url')->where('menu_id',request()->menu_id)->where('id','<>',request()->update_id)];
            }else{
                $rules['url']         = ['nullable','string',Rule::unique('modules','url')->where('menu_id',request()->menu_id)];
            }
            $rules['icon_class']      = ['nullable','string'];
            $rules['target']          = ['nullable','string'];
        }
        return $rules;
    }
}
