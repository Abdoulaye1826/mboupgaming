<?php

namespace App\Services;

use App\Enums\InvoiceStatus;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Sale;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class InvoiceService
{
    public function __construct(private readonly ActivityLogService $activityLog)
    {
    }

    public function paginate(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        return Invoice::query()
            ->with(['customer', 'sale'])
            ->when($filters['search'] ?? null, function ($query, $search) {
                $query->where('invoice_number', 'like', "%{$search}%")
                    ->orWhereHas('customer', fn ($q) => $q->where('full_name', 'like', "%{$search}%"));
            })
            ->when($filters['status'] ?? null, function ($query, $status) {
                $query->where('status', $status);
            })
            ->when($filters['customer_id'] ?? null, function ($query, $customerId) {
                $query->where('customer_id', $customerId);
            })
            ->orderByDesc('issued_at')
            ->paginate($perPage)
            ->withQueryString();
    }

    public function getCustomers()
    {
        return Customer::orderBy('full_name')->get();
    }

    public function getAvailableSales(?Sale $currentSale = null)
    {
        $query = Sale::query()
            ->orderByDesc('sale_date');

        if ($currentSale !== null) {
            $query->where(function ($subQuery) use ($currentSale) {
                $subQuery->whereDoesntHave('invoice')
                         ->orWhere('id', $currentSale->id);
            });
        } else {
            $query->whereDoesntHave('invoice');
        }

        return $query->get();
    }

    public function create(array $data): Invoice
    {
        $data['invoice_number'] = $data['invoice_number'] ?? $this->generateInvoiceNumber();
        $data['status'] = $data['status'] ?? InvoiceStatus::Issued;

        $invoice = Invoice::create($data);

        $this->activityLog->log('create', $invoice, "Facture créée : {$invoice->invoice_number}");

        return $invoice;
    }

    public function createFromSale(Sale $sale): Invoice
    {
        return $this->create([
            'sale_id' => $sale->id,
            'customer_id' => $sale->customer_id,
            'issued_at' => $sale->sale_date,
            'subtotal_ht' => $sale->subtotal_ht,
            'tax_amount' => $sale->tax_amount,
            'total_ttc' => $sale->total_ttc,
            'status' => InvoiceStatus::Issued,
            'invoice_number' => $this->generateInvoiceNumberFromSale($sale),
        ]);
    }

    private function generateInvoiceNumberFromSale(Sale $sale): string
    {
        return preg_replace('/^V-/', 'F-', $sale->sale_number, 1);
    }

    public function update(Invoice $invoice, array $data): Invoice
    {
        $invoice->update($data);

        $this->activityLog->log('update', $invoice, "Facture modifiée : {$invoice->invoice_number}");

        return $invoice->fresh();
    }

    public function delete(Invoice $invoice): void
    {
        $invoiceNumber = $invoice->invoice_number;
        $invoice->delete();

        $this->activityLog->log('delete', null, "Facture supprimée : {$invoiceNumber}");
    }

    private function generateInvoiceNumber(): string
    {
        $date = now()->format('Ymd');
        $count = Invoice::whereDate('created_at', now()->toDateString())->count() + 1;

        return sprintf('F-%s-%04d', $date, $count);
    }
}
