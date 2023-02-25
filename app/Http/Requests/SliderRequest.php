<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SliderRequest extends FormRequest
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
                'max:160',
                Rule::unique('slider')->ignore($this->id),
            ],
            'url' => 'required|url',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'status' => 'nullable',
            'image_uri' => 'nullable|image|mimes:jpg,jpeg,png,gif',
            'description' => 'max:160',
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
            'name' => __('trans.slider.title'),
            'url' => __('trans.slug'),
            'start_date' => __('trans.start_date'),
            'end_date' => __('trans.end_date'),
            'image_uri' => __('trans.image.name'),
            'description' => __('trans.description'),
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'status' => $this->status ?? config('constant.status.inactive'),
            'url' => $this->url,
        ]);
    }
}
