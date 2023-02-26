<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AttributeRequest extends FormRequest
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
                Rule::unique('attribute')->ignore($this->id),
            ],
            'slug' => 'nullable',
            'category_id' => 'nullable',
            'variation.*.value' => 'nullable|max:30',
            'description' => 'max:160',
        ];
    }

    public function messages(): array
    {
        return [
            'required' => __('trans.validation.required'),
            'max' => __('trans.validation.max.string'),
            'unique' => __('trans.validation.unique'),
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => __('trans.attribute.title'),
            'description' => __('trans.description'),
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'slug' => str()->slug($this->name),
        ]);
    }
}
