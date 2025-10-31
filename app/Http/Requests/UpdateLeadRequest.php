<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateLeadRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'nullable|string|max:255',
            'email' => 'nullable|email|unique:leads,email,' . $this->route('id'),
            'phone' => 'nullable|string|unique:leads,phone,' . $this->route('id'),
            'address' => 'nullable|string|max:255',
            'address_complement' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'zip' => 'nullable|string|max:255',
            'user_id' => 'nullable|exists:users,id',
            'status' => 'nullable|in:in_progress,successful,unsuccessful',
            'notes' => 'nullable|string|max:255',
            'desired_credit_amount' => 'nullable|numeric|min:0',
        ];
    }
}
