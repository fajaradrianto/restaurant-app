<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Table;
use App\Models\Menu;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $today = now()->toDateString();

        $totalOrdersToday  = Order::whereDate('created_at', $today)->count();
        $revenueToday      = Order::whereDate('created_at', $today)->where('payment_status', 'paid')->sum('total');
        $pendingOrders     = Order::whereIn('status', ['new', 'confirmed'])->count();
        $preparingOrders   = Order::where('status', 'preparing')->count();
        $tablesOccupied    = Table::where('status', 'occupied')->count();
        $tablesTotal       = Table::count();

        // Grafik 7 hari terakhir
        $last7Days = collect(range(6, 0))->map(function ($daysAgo) {
            $date = now()->subDays($daysAgo)->toDateString();
            return [
                'date'    => now()->subDays($daysAgo)->format('d M'),
                'orders'  => Order::whereDate('created_at', $date)->count(),
                'revenue' => Order::whereDate('created_at', $date)->where('payment_status', 'paid')->sum('total'),
            ];
        });

        // Pesanan terbaru
        $recentOrders = Order::with(['table', 'customer'])
            ->latest()
            ->take(10)
            ->get();

        return view('admin.dashboard', compact(
            'totalOrdersToday', 'revenueToday', 'pendingOrders',
            'preparingOrders', 'tablesOccupied', 'tablesTotal',
            'last7Days', 'recentOrders'
        ));
    }
}