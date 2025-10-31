<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateLeadRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:leads,email',
            'phone' => 'required|string|unique:leads,phone',
            'city' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
            'address_complement' => 'nullable|string|max:255',
            'zip' => 'nullable|string|max:255',
            'next_call_date' => 'required|date|after_or_equal:now',
            'notes' => 'required|string|max:255',
            'lead_type_id' => 'required|exists:lead_types,id',
            'desired_credit_amount' => 'nullable|numeric|min:0',
        ];
    }
}
