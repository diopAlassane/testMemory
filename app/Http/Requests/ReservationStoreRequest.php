<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReservationStoreRequest extends FormRequest
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
            'number_table' => ['required', 'numeric'],
            'date' => ['required', 'date'],
            'time' => ['required', 'date'],
            'number_place' => ['required', 'max:255', 'string'],
            'client_id' => ['required', 'exists:clients,id'],
        ];
    }
}
