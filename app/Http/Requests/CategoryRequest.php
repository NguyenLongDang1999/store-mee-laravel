<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CategoryRequest extends FormRequest
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
                Rule::unique('category')->ignore($this->id)
            ],
            'slug' => 'nullable',
            'status' => 'nullable',
            'popular' => 'nullable',
            'image_uri' => 'nullable|image|mimes:jpg,jpeg,png,gif',
            'parent_id' => 'nullable',
            'description' => 'max:160',
            'meta_title' => 'max:60',
            'meta_keyword' => 'max:60',
            'meta_description' => 'max:160',
        ];
    }

    public function messages(): array
    {
        return [
            'required' => __('trans.validation.required'),
            'max' => __('trans.validation.max.string'),
            'unique' => __('trans.validation.unique'),
            'image' => __('trans.validation.image'),
            'mimes' => __('trans.validation.mimes'),
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => __('trans.category.title'),
            'image_uri' => __('trans.image.name'),
            'description' => __('trans.description'),
            'meta_title' => __('trans.meta.title'),
            'meta_keyword' => __('trans.meta.keyword'),
            'meta_description' => __('trans.meta.description'),
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'status' => $this->status ?? config('constant.status.inactive'),
            'popular' => $this->popular ?? config('constant.popular.inactive'),
            'slug' => str()->slug($this->name)
        ]);
    }
}
