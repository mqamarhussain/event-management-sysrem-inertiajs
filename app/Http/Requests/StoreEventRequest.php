<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEventRequest extends FormRequest
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
            'venue_id' => ['required'],
            'event' => ['required', 'max:255'],
            'description' => ['required'],
            'event_date' => ['required', 'date', 'after_or_equal:today'],
            'type' => ['required', 'in:1,2'],
            'audience_capacity' => ['required', 'numeric'],
            'payment_type' => ['required', 'in:1,2'],
            'amount' => ['required', 'numeric'],
            'banner' => ['required', 'image'],
        ];
    }
}
