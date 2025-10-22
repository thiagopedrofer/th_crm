<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateClientRequest extends FormRequest
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
            'email' => 'nullable|email|unique:clients,email',
            'phone' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'zip' => 'nullable|string|max:255',
            'payment_method' => 'required|string|in:cash,pix,credit_card,debit_card,bank_slip,crypto_currency',
            'due_day' => 'required|integer|min:1|max:31',
            'assembly_day' => 'required|integer|min:1|max:31',
            'credit_amount' => 'required|numeric',
            'lead_id' => 'nullable|exists:leads,id',
        ];
    }
}
