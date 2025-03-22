<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Table extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'qr_code',
        'is_occupied'
    ];

    protected $casts = [
        'is_occupied' => 'boolean'
    ];

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function scores(): HasMany
    {
        return $this->hasMany(Score::class);
    }
}
