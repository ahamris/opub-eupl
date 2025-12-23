<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SettingRequest extends FormRequest
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
        $settingId = $this->route('setting')?->id;
        
        $keyRule = 'required|string|max:255';
        if ($settingId) {
            $keyRule .= '|unique:settings,_key,' . $settingId;
        } else {
            $keyRule .= '|unique:settings,_key';
        }

        return [
            '_key' => $keyRule,
            '_value' => 'nullable|string',
            'group' => 'required|string|max:255',
        ];
    }

    /**
     * Get the validation messages that apply to the request.
     */
    public function messages(): array
    {
        return [
            '_key.required' => 'Key is required.',
            '_key.unique' => 'This key is already taken.',
            '_key.max' => 'Key may not be greater than 255 characters.',
            'group.required' => 'Group is required.',
            'group.max' => 'Group may not be greater than 255 characters.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            '_key' => 'Key',
            '_value' => 'Value',
            'group' => 'Group',
        ];
    }
}
