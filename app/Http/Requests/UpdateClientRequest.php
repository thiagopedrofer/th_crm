<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateClientRequest extends FormRequest
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
            'email' => 'nullable|email|unique:clients,email,' . $this->route('id'),
            'phone' => 'nullable|string|max:255|unique:clients,phone,' . $this->route('id'),
            'address' => 'nullable|string|max:255',
            'address_complement' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'zip' => 'nullable|string|max:255',
            'payment_status' => 'nullable|string|in:confirmed,pending,awaiting_payment',
            'payment_method' => 'nullable|string|in:cash,pix,credit_card,debit_card,bank_slip,crypto_currency',
            'due_day' => 'nullable|integer|min:1|max:31',
            'assembly_day' => 'nullable|integer|min:1|max:31',
            'credit_amount' => 'nullable|numeric|min:0',
            'lead_id' => 'nullable|exists:leads,id',
        ];
    }
}
