<?php

namespace Humweb\Pages\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PageSaveRequest extends FormRequest
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
            'title'     => 'required',
            'slug'      => 'required',
            //'uri'       => 'required|unique:pages',
            'content'   => 'required',
            'published' => 'boolean',
            'is_index'  => 'boolean',
        ];

        if ($this->id) {
            //$rules['uri'] .= ',uri,'.$this->id;
        }

        return $rules;
    }
}
