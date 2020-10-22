<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BorrowRequest extends FormRequest
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
            'borrow_date' => 'required|date|after:yesterday',
            'return_date' => 'required|date|after:borrow_date'
        ];
    }

    public function messages()
    {
        return [
            'borrow_date.required' => trans('main.validate.borrow_date_required'),
            'return_date.required' => trans('main.validate.return_date_required'),
            'borrow_date.after' => trans('main.validate.borrow_date_after'),
            'return_date.after' => trans('main.validate.return_date_after'),
        ];
    }
}
