<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEventRequest extends FormRequest
{
    public function authorize(): bool { return auth()->check() && auth()->user()->isAdmin(); }

    public function rules(): array
    {
        return [
            'title'       => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'location'    => ['required', 'string', 'max:255'],
            'event_date'  => ['required', 'date', 'after:now'],
            'price'       => ['required', 'numeric', 'min:0'],
            'total_seats' => ['required', 'integer', 'min:1', 'max:10000'],
            'status'      => ['nullable', 'in:active,cancelled,completed'],
        ];
    }
}
