<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProcessPaymentRequest extends FormRequest
{
    public function authorize(): bool { return auth()->check(); }

    public function rules(): array
    {
        return [
            'booking_id'     => ['required', 'integer', 'exists:bookings,id'],
            'payment_method' => ['required', 'in:credit_card,debit_card,bank_transfer'],
            'card_name'      => ['required_if:payment_method,credit_card,debit_card', 'nullable', 'string'],
            'card_number'    => ['required_if:payment_method,credit_card,debit_card', 'nullable', 'string'],
        ];
    }
}
