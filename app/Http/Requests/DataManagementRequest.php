<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DataManagementRequest extends FormRequest
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
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|string|max:255',
            'status' => 'required|string|in:active,inactive,archived',
            'data' => 'nullable|array',
            'priority' => 'nullable|integer|min:0',
        ];
    }

    /**
     * Get the validation messages that apply to the request.
     */
    public function messages(): array
    {
        return [
            'title.required' => 'Title is required.',
            'title.max' => 'Title may not be greater than 255 characters.',
            'type.required' => 'Type is required.',
            'status.required' => 'Status is required.',
            'status.in' => 'Status must be active, inactive, or archived.',
            'priority.integer' => 'Priority must be a number.',
            'priority.min' => 'Priority must be at least 0.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'title' => 'Title',
            'description' => 'Description',
            'type' => 'Type',
            'status' => 'Status',
            'data' => 'Data',
            'priority' => 'Priority',
        ];
    }
}
