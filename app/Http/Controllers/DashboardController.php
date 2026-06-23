<?php

namespace App\Http\Controllers;

use App\Services\DashboardService;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __construct(
        private readonly DashboardService $dashboardService
    ) {}

    public function index(): View
    {
        return view('dashboard.index', [
            'stats' => $this->dashboardService->getStats(),
            'salesByMonth' => $this->dashboardService->getSalesByMonth(),
            'salesByCategory' => $this->dashboardService->getSalesByCategory(),
            'topProducts' => $this->dashboardService->getTopProducts(),
            'stockAlerts' => $this->dashboardService->getStockAlerts(),
        ]);
    }
}
