<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VariationRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'max:50',
            ],
            'attribute_id' => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            'required' => __('trans.validation.required'),
            'max' => __('trans.validation.max.string'),
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => __('trans.attribute.title'),
            'attribute_id' => __('trans.attribute.name'),
        ];
    }
}
