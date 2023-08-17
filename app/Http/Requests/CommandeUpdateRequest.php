<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CommandeUpdateRequest extends FormRequest
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
     */
    public function rules(): array
    {
        return [
            'date' => ['required', 'date'],
            'time' => ['required', 'date'],
            'drink' => ['required', 'max:255', 'string'],
            'dessert' => ['required', 'max:255', 'string'],
            'food' => ['required', 'max:255', 'string'],
            'client_id' => ['required', 'exists:clients,id'],
        ];
    }
}
