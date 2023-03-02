<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductRequest extends FormRequest
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
                'max:100',
                Rule::unique('product')->ignore($this->id),
            ],
            'sku' => 'required',
            'slug' => 'nullable',
            'status' => 'nullable',
            'popular' => 'nullable',
            'image_uri' => 'nullable|image|mimes:jpg,jpeg,png,gif',
            'category_id' => 'nullable',
            'brand_id' => 'nullable',
            'view' => 'nullable',
            'quantity' => 'integer',
            'price' => 'required',
            'type_discount' => 'required',
            'price_discount' => 'required',
            'content' => 'nullable',
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
            'name' => __('trans.product.title'),
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
            'view' => 0,
            'slug' => str()->slug($this->name),
            'price' => (int) str()->replace(',', '', $this->price),
            'price_discount' => (int) str()->replace(',', '', $this->price_discount)
        ]);
    }
}
