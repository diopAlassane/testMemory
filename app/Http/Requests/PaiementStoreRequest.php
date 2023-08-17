<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PaiementStoreRequest extends FormRequest
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
            'price' => ['required', 'numeric'],
            'client_id' => ['required', 'exists:clients,id'],
            'print_pdf' => ['required', 'max:255', 'string'],
        ];
    }
}
