<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Table;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['table', 'customer', 'items']);

        // Filter status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter payment
        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        // Filter tanggal
        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhereHas('customer', fn($cq) => $cq->where('name', 'like', "%{$search}%"));
            });
        }

        $orders = $query->latest()->paginate(20);
        return view('admin.orders.index', compact('orders'));
    }

    public function show($id)
    {
        $order = Order::with(['table', 'customer', 'items.menu'])->findOrFail($id);
        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        $request->validate([
            'status'         => 'required|in:new,confirmed,preparing,ready,served,completed,cancelled',
            'payment_status' => 'nullable|in:pending,paid,failed',
        ]);

        $data = ['status' => $request->status];

        if ($request->filled('payment_status')) {
            $data['payment_status'] = $request->payment_status;
            if ($request->payment_status === 'paid' && !$order->paid_at) {
                $data['paid_at'] = now();
            }
        }

        $order->update($data);

        // Jika status completed atau cancelled, meja jadi available
        if (in_array($request->status, ['completed', 'cancelled'])) {
            Table::where('id', $order->table_id)->update(['status' => 'available']);
        }

        // Jika status new -> confirmed/preparing, pastikan meja occupied
        if (in_array($request->status, ['new', 'confirmed', 'preparing', 'ready', 'served'])) {
            Table::where('id', $order->table_id)->update(['status' => 'occupied']);
        }

        return back()->with('success', 'Status pesanan diperbarui');
    }

    // Halaman print untuk dapur
    public function print($id)
    {
        $order = Order::with(['table', 'customer', 'items'])->findOrFail($id);
        return view('admin.orders.print', compact('order'));
    }

    // Kitchen Display System
public function kitchenDisplay()
{
    // Ambil pesanan yang statusnya baru, dikonfirmasi, atau sedang dimasak
    $orders = Order::with(['table', 'items'])
        ->whereIn('status', ['new', 'confirmed', 'preparing'])
        ->orderByRaw("FIELD(status, 'preparing', 'confirmed', 'new')")
        ->orderBy('created_at', 'asc')
        ->get();

    // Hitung yang sudah siap
    $readyCount = Order::where('status', 'ready')->count();

    return view('admin.orders.kitchen', compact('orders', 'readyCount'));
}

public function kitchenUpdateStatus(Request $request, $id)
{
    $order = Order::findOrFail($id);

    $nextStatus = match($order->status) {
        'new' => 'preparing',
        'confirmed' => 'preparing',
        'preparing' => 'ready',
        default => null,
    };

    if ($nextStatus) {
        $order->update(['status' => $nextStatus]);
    }

    return back();
}
}