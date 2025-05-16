<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $hidden = [
        'table_id',
        'customer_id',
        'waiter_id',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'table_id',
        'customer_id',
        'waiter_id',
        'status',
        'total_amount',
        'notes',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'total_amount' => 'decimal:2',
    ];

    /**
     * Get the table that owns the order.
     */
    public function table()
    {
        return $this->belongsTo(Table::class);
    }

    /**
     * Get the customer that owns the order.
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Get the waiter that owns the order.
     */
    public function waiter()
    {
        return $this->belongsTo(User::class, 'waiter_id');
    }

    /**
     * Get the items for the order.
     */
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Scope a query to only include orders with status.
     */
    public function scopeWithStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope a query to only include orders for a specific waiter.
     */
    public function scopeForWaiter($query, $waiterId)
    {
        return $query->where('waiter_id', $waiterId);
    }

    /**
     * Scope a query to only include orders for a specific table.
     */
    public function scopeForTable($query, $tableId)
    {
        return $query->where('table_id', $tableId);
    }

    /**
     * Scope a query to only include orders for a specific customer.
     */
    public function scopeForCustomer($query, $customerId)
    {
        return $query->where('customer_id', $customerId);
    }

    /**
     * Scope a query to only include orders for today.
     */
    public function scopeToday($query)
    {
        return $query->whereDate('created_at', now()->toDateString());
    }

    /**
     * Scope a query to only include orders for this week.
     */
    public function scopeThisWeek($query)
    {
        return $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
    }

    /**
     * Scope a query to only include orders for this month.
     */
    public function scopeThisMonth($query)
    {
        return $query->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year);
    }
}