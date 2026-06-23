<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSaleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'customer_id' => ['nullable', 'exists:customers,id'],
            'sale_date' => ['required', 'date'],
            'discount_amount' => ['nullable', 'numeric', 'min:0'],
            'tax_rate' => ['nullable', 'numeric', 'min:0'],
            'subtotal_ht' => ['required', 'numeric', 'min:0'],
            'tax_amount' => ['required', 'numeric', 'min:0'],
            'total_ttc' => ['required', 'numeric', 'min:0'],
            'status' => ['required', 'in:draft,validated,cancelled'],
            'notes' => ['nullable', 'string'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'discount_amount' => $this->filled('discount_amount') ? $this->input('discount_amount') : 0,
            'tax_rate' => $this->filled('tax_rate') ? $this->input('tax_rate') : 18.0,
            'subtotal_ht' => $this->filled('subtotal_ht') ? $this->input('subtotal_ht') : 0,
            'tax_amount' => $this->filled('tax_amount') ? $this->input('tax_amount') : 0,
            'total_ttc' => $this->filled('total_ttc') ? $this->input('total_ttc') : 0,
        ]);
    }
}
