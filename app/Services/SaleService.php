<?php

namespace App\Services;

use App\Enums\SaleStatus;
use App\Models\Customer;
use App\Models\Sale;
use App\Services\InvoiceService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class SaleService
{
    public function __construct(
        private readonly ActivityLogService $activityLog,
        private readonly InvoiceService $invoiceService
    ) {
    }

    public function paginate(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        return Sale::query()
            ->with(['customer', 'user'])
            ->when($filters['search'] ?? null, function ($query, $search) {
                $query->where(function ($query) use ($search) {
                    $query->where('sale_number', 'like', "%{$search}%")
                        ->orWhereHas('customer', fn ($q) => $q->where('full_name', 'like', "%{$search}%"));
                });
            })
            ->when($filters['status'] ?? null, function ($query, $status) {
                $query->where('status', $status);
            })
            ->when($filters['customer_id'] ?? null, function ($query, $customerId) {
                $query->where('customer_id', $customerId);
            })
            ->orderByDesc('sale_date')
            ->paginate($perPage)
            ->withQueryString();
    }

    public function getCustomers()
    {
        return Customer::orderBy('full_name')->get();
    }

    public function create(array $data, int $userId): Sale
    {
        $data['sale_number'] = $this->generateSaleNumber();
        $data['user_id'] = $userId;
        $data['status'] = $data['status'] ?? SaleStatus::Draft;

        $sale = Sale::create($data);

        $this->activityLog->log('create', $sale, "Vente créée : {$sale->sale_number}");

        if ($sale->status === SaleStatus::Validated) {
            $this->invoiceService->createFromSale($sale);
        }

        return $sale;
    }

    public function update(Sale $sale, array $data): Sale
    {
        $previousStatus = $sale->status;
        $sale->update($data);

        $this->activityLog->log('update', $sale, "Vente mise à jour : {$sale->sale_number}");

        if ($sale->status === SaleStatus::Validated && $previousStatus !== SaleStatus::Validated && !$sale->invoice()->exists()) {
            $this->invoiceService->createFromSale($sale);
        }

        return $sale->fresh();
    }

    public function delete(Sale $sale): void
    {
        if ($sale->status === SaleStatus::Validated) {
            throw new \RuntimeException('Impossible de supprimer une vente déjà validée.');
        }

        $saleNumber = $sale->sale_number;
        $sale->delete();

        $this->activityLog->log('delete', null, "Vente supprimée : {$saleNumber}");
    }

    private function generateSaleNumber(): string
    {
        $date = now()->format('Ymd');
        $count = Sale::whereDate('created_at', now()->toDateString())->count() + 1;

        return sprintf('V-%s-%04d', $date, $count);
    }
}
