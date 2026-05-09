<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEventRequest extends FormRequest
{
    public function authorize(): bool { return auth()->check() && auth()->user()->isAdmin(); }

    public function rules(): array
    {
        return [
            'title'       => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'location'    => ['required', 'string', 'max:255'],
            'event_date'  => ['required', 'date'],
            'price'       => ['required', 'numeric', 'min:0'],
            'status'      => ['required', 'in:active,cancelled,completed'],
        ];
    }
}
