<?php

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreOfflineMembershipRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return (bool) ($this->user()?->is_admin);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['required', 'string', 'max:25'],
            'address_line1' => ['required', 'string', 'max:255'],
            'address_line2' => ['nullable', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:100'],
            'state' => ['required', 'string', 'max:100'],
            'postal_code' => ['required', 'string', 'max:20'],
            'fee_paid' => ['required', 'numeric', 'min:0'],
            'validity_years' => ['required', 'integer', 'min:1', 'max:20'],
            'starts_at' => ['required', 'date'],
            'payment_method' => ['required', 'string', 'in:cash,upi,bank_transfer,cheque,offline'],
            'payment_id' => ['nullable', 'string', 'max:255'],
            'invoice_number' => ['nullable', 'string', 'max:100', 'unique:memberships,invoice_number'],
            'prepaid_months' => ['nullable', 'integer', 'min:0', 'max:60'],
            'prepaid_until' => ['nullable', 'date'],
            'dispatch_current_month' => ['nullable', 'boolean'],
            'send_welcome_email' => ['nullable', 'boolean'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ];
    }
}
