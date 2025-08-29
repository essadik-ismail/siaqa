<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Usage extends Model
{
    use HasFactory;

    protected $table = 'usage';

    protected $fillable = [
        'tenant_id',
        'feature',
        'usage_count',
        'limit',
        'period',
    ];

    protected $casts = [
        'usage_count' => 'integer',
        'limit' => 'integer',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function incrementUsage(int $count = 1)
    {
        $this->usage_count += $count;
        $this->save();
    }

    public function hasReachedLimit(): bool
    {
        return $this->usage_count >= $this->limit;
    }

    public function getRemainingUsage(): int
    {
        return max(0, $this->limit - $this->usage_count);
    }
} 