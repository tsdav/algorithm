<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DocumentValidation extends FormRequest implements \Countable
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
            'document.*' => 'required|mimes:doc,docx,pdf|max:5120'
        ];
    }

    public function count()
    {
        // TODO: Implement count() method.
    }
}
