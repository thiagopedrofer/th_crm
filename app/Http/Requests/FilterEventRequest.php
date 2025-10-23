<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FilterEventRequest extends FormRequest
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
            'lead_name' => 'nullable|string',
            'per_page' => 'nullable|integer|min:1|max:10',
            'page' => 'nullable|integer|min:1',
            'progress' => 'nullable|string|in:overdue,due_soon,on_time,today',
            'next_call_date' => 'nullable|date',
            'next_call_date_from' => 'nullable|date',
            'next_call_date_to' => 'nullable|date',
            'user_id' => 'nullable|exists:users,id',
            'lead_id' => 'nullable|exists:leads,id',
        ];
    }
}
