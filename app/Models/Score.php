<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Score extends Model
{
    use HasFactory;

    protected $fillable = [
        'table_id',
        'player1_name',
        'player2_name',
        'player3_name',
        'player4_name',
        'player1_score',
        'player2_score',
        'player3_score',
        'player4_score',
        'is_active'
    ];

    protected $casts = [
        'player1_score' => 'integer',
        'player2_score' => 'integer',
        'player3_score' => 'integer',
        'player4_score' => 'integer',
        'is_active' => 'boolean'
    ];

    public function table(): BelongsTo
    {
        return $this->belongsTo(Table::class);
    }
}
