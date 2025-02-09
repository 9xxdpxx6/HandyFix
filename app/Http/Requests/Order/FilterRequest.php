<?php

namespace App\Http\Requests\Order;

use Illuminate\Foundation\Http\FormRequest;

class FilterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules()
    {
        return [
            'keyword' => 'nullable|string|max:255',
            'status' => 'nullable|integer|exists:statuses,id',
            'sort' => 'nullable|string|in:date_asc,date_desc,default',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
        ];
    }

    public function messages()
    {
        return [
            'status.exists' => 'Выбранный статус не существует.',
            'sort.in' => 'Неверный параметр сортировки.',
        ];
    }
}
