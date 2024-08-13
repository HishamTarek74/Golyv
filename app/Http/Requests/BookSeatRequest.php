<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BookSeatRequest extends FormRequest
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
            'trip_id' => 'required|integer|exists:trips,id',
            'seat_id' => 'required|integer|exists:seats,id',
            'from_city_id' => 'required|integer|exists:cities,id',
            'to_city_id' => 'required|integer|exists:cities,id|different:from_city_id',
        ];
    }
}
