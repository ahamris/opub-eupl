<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BlogRequest extends FormRequest
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
        $blogId = $this->route('blog')?->id;
        
        $slugRule = 'nullable|string|max:255';
        if ($blogId) {
            $slugRule .= '|unique:blogs,slug,' . $blogId;
        } else {
            $slugRule .= '|unique:blogs,slug';
        }

        return [
            'blog_category_id' => 'required|exists:blog_categories,id',
            'author_id' => 'required|exists:users,id',
            'title' => 'required|string|max:255',
            'slug' => $slugRule,
            'short_body' => 'required|string|min:10',
            'long_body' => 'required|string|min:20',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'is_active' => 'nullable|boolean',
            'is_featured' => 'nullable|boolean',
            'og_title' => 'nullable|string|max:255',
            'og_description' => 'nullable|string|max:500',
            'og_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_active' => $this->has('is_active') ? true : false,
            'is_featured' => $this->has('is_featured') ? true : false,
        ]);
    }

    /**
     * Get the validation messages that apply to the request.
     */
    public function messages(): array
    {
        return [
            'blog_category_id.required' => 'Blog category is required.',
            'blog_category_id.exists' => 'Selected blog category does not exist.',
            'author_id.required' => 'Author is required.',
            'author_id.exists' => 'Selected author does not exist.',
            'title.required' => 'Title is required.',
            'title.max' => 'Title may not be greater than 255 characters.',
            'slug.unique' => 'This slug is already taken.',
            'short_body.required' => 'Short description is required.',
            'short_body.min' => 'Short description must be at least 10 characters.',
            'long_body.required' => 'Content is required.',
            'long_body.min' => 'Content must be at least 20 characters.',
            'image.image' => 'File must be an image.',
            'image.mimes' => 'Image must be a file of type: jpeg, png, jpg, gif, webp.',
            'image.max' => 'Image may not be greater than 2MB.',
            'is_active.boolean' => 'Active status must be true or false.',
            'is_featured.boolean' => 'Featured status must be true or false.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'blog_category_id' => 'Blog category',
            'author_id' => 'Author',
            'title' => 'Title',
            'slug' => 'Slug',
            'short_body' => 'Short description',
            'long_body' => 'Content',
            'image' => 'Image',
            'is_active' => 'Active status',
            'is_featured' => 'Featured status',
            'og_title' => 'Open Graph title',
            'og_description' => 'Open Graph description',
            'og_image' => 'Open Graph image',
        ];
    }
}