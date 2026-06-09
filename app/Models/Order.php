<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number', 'table_id', 'customer_id', 'total',
        'payment_method', 'payment_status', 'status', 'notes', 'paid_at'
    ];

    protected $casts = [
        'paid_at' => 'datetime',
    ];

    protected static function booted()
    {
        static::creating(function ($order) {
            $order->order_number = 'ORD-' . date('Ymd') . '-' . strtoupper(Str::random(5));
        });
    }

    public function table()
    {
        return $this->belongsTo(Table::class, 'table_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class, 'order_id');
    }

    public function getFormattedTotalAttribute()
    {
        return 'Rp ' . number_format($this->total, 0, ',', '.');
    }

    public function getStatusLabelAttribute()
    {
        $labels = [
            'new' => 'Baru',
            'confirmed' => 'Dikonfirmasi',
            'preparing' => 'Dimasak',
            'ready' => 'Siap Saji',
            'served' => 'Disajikan',
            'completed' => 'Selesai',
            'cancelled' => 'Dibatalkan',
        ];
        return $labels[$this->status] ?? $this->status;
    }

    public function getPaymentStatusLabelAttribute()
    {
        $labels = [
            'pending' => 'Belum Bayar',
            'paid' => 'Sudah Bayar',
            'failed' => 'Gagal',
        ];
        return $labels[$this->payment_status] ?? $this->payment_status;
    }

    public function getPaymentMethodLabelAttribute()
    {
        $labels = [
            'qris' => 'QRIS',
            'virtual_account' => 'Virtual Account',
            'cash' => 'Tunai (Cashier)',
        ];
        return $labels[$this->payment_method] ?? $this->payment_method;
    }

    public function generateVirtualAccount()
    {
        return '8877' . str_pad($this->id, 12, '0', STR_PAD_LEFT);
    }
}