<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBookingRequest extends FormRequest
{
    public function authorize(): bool { return auth()->check(); }

    public function rules(): array
    {
        return [
            'event_id'        => ['required', 'integer', 'exists:events,id'],
            'seats_requested' => ['required', 'integer', 'min:1', 'max:10'],
        ];
    }
}
