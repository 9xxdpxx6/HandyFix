<?php

namespace App\Http\Requests\Order;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
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
    public function rules(): array
    {
        return [
            'customer_id' => 'required|exists:customers,id',
            'status_id' => 'required|exists:statuses,id',
            'info' => 'nullable|string',
            'purchases' => 'required|array',
            'purchases.*.name' => 'required|string|max:255',
            'purchases.*.material_price' => 'required|numeric|min:0',
            'purchases.*.service_price' => 'required|numeric|min:0',
            'date' => 'nullable|date_format:Y-m-d\TH:i',
        ];
    }

    public function messages(): array
    {
        return [
            'customer_id.required' => 'Поле "Покупатель" не может быть пустым.',
            'customer_id.exists' => 'Выбранный покупатель не существует.',
            'status_id.required' => 'Поле "Статус" не может быть пустым.',
            'status_id.exists' => 'Выбранный статус не существует.',
            'purchases.required' => 'Необходимо добавить хотя бы одну покупку.',
            'purchases.array' => 'Покупки должны быть в формате списка.',
            'purchases.*.name.required' => 'Поле "Название покупки" не может быть пустым.',
            'purchases.*.name.string' => 'Поле "Название покупки" должно быть строкой.',
            'purchases.*.name.max' => 'Поле "Название покупки" не может превышать 255 символов.',
            'purchases.*.material_price.required' => 'Поле "Стоимость материала" не может быть пустым.',
            'purchases.*.material_price.numeric' => 'Поле "Стоимость материала" должно быть числом.',
            'purchases.*.material_price.min' => 'Поле "Стоимость материала" не может быть отрицательным.',
            'purchases.*.service_price.required' => 'Поле "Стоимость работы" не может быть пустым.',
            'purchases.*.service_price.numeric' => 'Поле "Стоимость работы" должно быть числом.',
            'purchases.*.service_price.min' => 'Поле "Стоимость работы" не может быть отрицательным.',
            'date.required' => 'Поле "Дата и время" не может быть пустым.',
            'date.date_format' => 'Поле "Дата и время" должно соответствовать формату Y-m-d H:i:s.',
        ];
    }
}
