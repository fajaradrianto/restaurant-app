<?php

namespace App\Http\Controllers;

use App\Models\Table;
use App\Models\Menu;
use App\Models\MenuCategory;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomerOrderController extends Controller
{
    // Step 1: Tampilkan form data diri setelah scan QR
    public function showCustomerForm($tableId)
    {
        $table = Table::findOrFail($tableId);

        // Jika customer sudah mengisi form di session, langsung ke menu
        if (session()->has('customer_data') && session('table_id') == $tableId) {
            return redirect()->route('customer.menu', $tableId);
        }

        return view('customer.form', compact('table'));
    }

    // Step 2: Simpan data diri ke session, tampilkan menu
    public function storeCustomer(Request $request, $tableId)
    {
        $request->validate([
            'name'  => 'required|string|max:100',
            'phone' => 'required|numeric|digits_between:10,15',
            'email' => 'required|email|max:150',
        ]);

        $table = Table::findOrFail($tableId);

        session([
            'table_id'      => $tableId,
            'customer_data' => [
                'name'  => $request->name,
                'phone' => $request->phone,
                'email' => $request->email,
            ],
        ]);

        return redirect()->route('customer.menu', $tableId);
    }

    // Step 3: Tampilkan list menu
    public function showMenu($tableId)
    {
        if (!session()->has('customer_data') || session('table_id') != $tableId) {
            return redirect()->route('customer.form', $tableId);
        }

        $table = Table::findOrFail($tableId);
        $categories = MenuCategory::with(['menus' => function ($q) {
            $q->where('is_available', true)->orderBy('name');
        }])->orderBy('sort_order')->get();

        $cart = session('cart', []);

        return view('customer.menu', compact('table', 'categories', 'cart'));
    }

    // Tambah item ke cart (via AJAX atau form)
    public function addToCart(Request $request, $tableId)
    {
        $menu = Menu::findOrFail($request->menu_id);

        if (!$menu->is_available) {
            return back()->withErrors(['Menu tidak tersedia']);
        }

        $cart = session('cart', []);
        $quantity = (int) $request->quantity;

        if (isset($cart[$menu->id])) {
            $cart[$menu->id]['quantity'] += $quantity;
        } else {
            $cart[$menu->id] = [
                'id'       => $menu->id,
                'name'     => $menu->name,
                'price'    => $menu->price,
                'quantity' => $quantity,
            ];
        }

        session(['cart' => $cart]);

        return back()->with('success', "{$menu->name} ditambahkan ke pesanan");
    }

    // Update jumlah item di cart
    public function updateCart(Request $request, $tableId)
    {
        $cart = session('cart', []);
        $menuId = $request->menu_id;
        $action = $request->action; // 'increase', 'decrease', 'remove'

        if (isset($cart[$menuId])) {
            if ($action === 'increase') {
                $cart[$menuId]['quantity']++;
            } elseif ($action === 'decrease') {
                $cart[$menuId]['quantity']--;
                if ($cart[$menuId]['quantity'] <= 0) {
                    unset($cart[$menuId]);
                }
            } elseif ($action === 'remove') {
                unset($cart[$menuId]);
            }
        }

        session(['cart' => $cart]);

        if (empty($cart)) {
            return redirect()->route('customer.menu', $tableId);
        }

        return back();
    }

    // Step 4: Tampilkan halaman checkout / ringkasan pesanan
    public function showCheckout($tableId)
    {
        if (!session()->has('customer_data') || session('table_id') != $tableId) {
            return redirect()->route('customer.form', $tableId);
        }

        $cart = session('cart', []);

        if (empty($cart)) {
            return redirect()->route('customer.menu', $tableId)->withErrors(['Keranjang pesanan kosong']);
        }

        $table = Table::findOrFail($tableId);
        $customerData = session('customer_data');
        $total = array_sum(array_map(fn($item) => $item['price'] * $item['quantity'], $cart));

        return view('customer.checkout', compact('table', 'customerData', 'cart', 'total'));
    }

    // Step 5: Proses pesanan, tampilkan halaman pembayaran
    public function processOrder(Request $request, $tableId)
    {
        $request->validate([
            'payment_method' => 'required|in:qris,virtual_account,cash',
            'notes'          => 'nullable|string|max:500',
        ]);

        if (!session()->has('customer_data') || session('table_id') != $tableId) {
            return redirect()->route('customer.form', $tableId);
        }

        $cart = session('cart', []);
        if (empty($cart)) {
            return redirect()->route('customer.menu', $tableId)->withErrors(['Keranjang kosong']);
        }

        $customerData = session('customer_data');
        $total = array_sum(array_map(fn($item) => $item['price'] * $item['quantity'], $cart));

        DB::beginTransaction();
        try {
            // Simpan atau cari customer
            $customer = Customer::firstOrCreate(
                ['email' => $customerData['email']],
                ['name' => $customerData['name'], 'phone' => $customerData['phone']]
            );

            // Buat order
            $order = Order::create([
                'table_id'       => $tableId,
                'customer_id'    => $customer->id,
                'total'          => $total,
                'payment_method' => $request->payment_method,
                'payment_status' => 'pending',
                'status'         => 'new',
                'notes'          => $request->notes,
            ]);

            // Simpan order items
            foreach ($cart as $item) {
                OrderItem::create([
                    'order_id'  => $order->id,
                    'menu_id'   => $item['id'],
                    'menu_name' => $item['name'],
                    'price'     => $item['price'],
                    'quantity'  => $item['quantity'],
                    'subtotal'  => $item['price'] * $item['quantity'],
                ]);
            }

            // Update status meja
            Table::where('id', $tableId)->update(['status' => 'occupied']);

            DB::commit();

            // Hapus cart dari session
            session()->forget('cart');

            return redirect()->route('customer.payment', [$tableId, $order->order_number]);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    // Step 6: Tampilkan halaman pembayaran
    public function showPayment($tableId, $orderNumber)
    {
        $order = Order::where('order_number', $orderNumber)
            ->where('table_id', $tableId)
            ->firstOrFail();

        $table = Table::findOrFail($tableId);

        return view('customer.payment', compact('order', 'table'));
    }

    // Step 7: Konfirmasi pembayaran (simulasi)
    public function confirmPayment(Request $request, $tableId, $orderNumber)
    {
        $order = Order::where('order_number', $orderNumber)
            ->where('table_id', $tableId)
            ->firstOrFail();

        // Simulasi: langsung tandai sebagai paid
        // Di produksi, QRIS/VA akan dikonfirmasi via webhook
        if ($order->payment_method === 'cash') {
            // Tunai: status tetap pending sampai kasir konfirmasi
        } else {
            // QRIS / VA: simulasi langsung paid
            $order->update([
                'payment_status' => 'paid',
                'paid_at'        => now(),
            ]);
        }

        // Hapus session customer
        session()->forget(['customer_data', 'table_id']);

        return redirect()->route('customer.success', [$tableId, $orderNumber]);
    }

    // Step 8: Halaman sukses
    public function showSuccess($tableId, $orderNumber)
    {
        $order = Order::where('order_number', $orderNumber)
            ->where('table_id', $tableId)
            ->firstOrFail();

        $table = Table::findOrFail($tableId);

        return view('customer.success', compact('order', 'table'));
    }
}