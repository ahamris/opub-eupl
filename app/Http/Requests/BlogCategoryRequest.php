<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BlogCategoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $blogCategoryId = $this->route('blogCategory')?->id;
        
        $slugRule = 'nullable|string|max:255';
        if ($blogCategoryId) {
            $slugRule .= '|unique:blog_categories,slug,' . $blogCategoryId;
        } else {
            $slugRule .= '|unique:blog_categories,slug';
        }

        return [
            'name' => 'required|string|max:255',
            'slug' => $slugRule,
            'description' => 'nullable|string',
            'color' => 'nullable|string|max:16|regex:/^#[0-9A-Fa-f]{6}$/',
        ];
    }

    /**
     * Get the validation messages that apply to the request.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Name is required.',
            'name.max' => 'Name may not be greater than 255 characters.',
            'slug.unique' => 'This slug is already taken.',
            'color.regex' => 'Color must be a valid hex color (e.g., #FF0000).',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'name' => 'Name',
            'slug' => 'Slug',
            'description' => 'Description',
            'color' => 'Color',
        ];
    }
}