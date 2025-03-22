<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'table_id',
        'status',
        'total_amount',
        'notes'
    ];

    protected $casts = [
        'total_amount' => 'decimal:2'
    ];

    public function table(): BelongsTo
    {
        return $this->belongsTo(Table::class);
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class)
            ->withPivot(['quantity', 'unit_price'])
            ->withTimestamps();
    }
}
