<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Override;

class PostRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            //
            'title' => ['required', 'max:100'],
            'slug' => ['nullable', Rule::unique('posts', 'slug')->ignore($this->route('post'))],
            'body' => ['required', 'min:10', 'max:500'],
            'is_published' => ['nullable', 'boolean'],
            'image' => ['nullable', 'image', 'mimes:jpg,png,jpeg'],

        ];
    }
    #[Override]
    public function prepareForValidation(): void
    {
        $this->merge([
            'is_published' => $this->boolean('is_published')
        ]);
    }
}
