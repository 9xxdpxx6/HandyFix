<?php

namespace App\Http\Requests\DayLedger;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
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
            'date' => 'required|date',
            'total' => 'nullable|numeric',
            'balance' => 'nullable|numeric',
            'materials' => 'nullable|array',
            'materials.*.incoming' => 'nullable|numeric',
            'materials.*.issuance' => 'nullable|numeric',
            'materials.*.waste' => 'nullable|numeric',
            'materials.*.balance' => 'nullable|numeric',
            'materials.*.description' => 'nullable|string',
            'services' => 'nullable|array',
            'services.*.service_type_id' => 'required_with:services|exists:service_types,id',
            'services.*.price' => 'required_with:services|numeric',
            'services.*.description' => 'nullable|string',
            'expenses' => 'nullable|array',
            'expenses.*.description' => 'nullable|string',
            'expenses.*.price' => 'required|numeric',
        ];
    }

    public function messages(): array
    {
        return [
            'date.required' => 'Поле "Дата" не может быть пустым.',
            'date.date' => 'Поле "Дата" должно содержать корректную дату.',
            'total.numeric' => 'Поле "Итог" должно быть числом.',
            'balance.numeric' => 'Поле "Остаток" должно быть числом.',
            'materials.*.incoming.numeric' => 'Поле "Приход" материала должно быть числом.',
            'materials.*.issuance.numeric' => 'Поле "Выдача" материала должно быть числом.',
            'materials.*.waste.numeric' => 'Поле "Угар" материала должно быть числом.',
            'materials.*.balance.numeric' => 'Поле "Остаток" материала должно быть числом.',
            'materials.*.description.string' => 'Поле "Описание" материала должно быть строкой.',
            'services.*.service_type_id.required_with' => 'Поле "Тип услуги" не может быть пустым, если добавлена услуга.',
            'services.*.service_type_id.exists' => 'Выбранный тип услуги не существует.',
            'services.*.price.required_with' => 'Поле "Стоимость услуги" не может быть пустым, если добавлена услуга.',
            'services.*.price.numeric' => 'Поле "Стоимость услуги" должно быть числом.',
            'services.*.description.string' => 'Поле "Название услуги" должно быть строкой.',
            'expenses.*.description.string' => 'Поле "Название расхода" должно быть строкой.',
            'expenses.*.price.required' => 'Поле "Стоимость расхода" не может быть пустым.',
            'expenses.*.price.numeric' => 'Поле "Стоимость расхода" должно быть числом.',
        ];
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $materials = $this->input('materials', []);
            $services = $this->input('services', []);
            $expenses = $this->input('expenses', []);

            if (empty($materials) && empty($services) && empty($expenses)) {
                $validator->errors()->add('materials', 'Необходимо добавить хотя бы одну запись в материалы, услуги или расходы.');
                $validator->errors()->add('services', 'Необходимо добавить хотя бы одну запись в материалы, услуги или расходы.');
                $validator->errors()->add('expenses', 'Необходимо добавить хотя бы одну запись в материалы, услуги или расходы.');
            }
        });
    }
}
