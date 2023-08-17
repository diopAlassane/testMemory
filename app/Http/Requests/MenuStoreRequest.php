<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MenuStoreRequest extends FormRequest
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
            'drink_list' => ['required', 'max:255', 'string'],
            'dessert_list' => ['required', 'max:255', 'string'],
            'food_list' => ['required', 'max:255', 'string'],
            'client_id' => ['required', 'exists:clients,id'],
        ];
    }
}
