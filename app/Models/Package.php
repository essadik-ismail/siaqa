<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Package extends Model
{
    use HasFactory;

    protected $table = 'packages';

    protected $fillable = [
        'tenant_id',
        'name',
        'description',
        'license_category',
        'theory_hours',
        'practical_hours',
        'price',
        'validity_days',
        'includes_exam',
        'includes_materials',
        'features',
        'is_active',
    ];

    protected $casts = [
        'theory_hours' => 'integer',
        'practical_hours' => 'integer',
        'price' => 'decimal:2',
        'validity_days' => 'integer',
        'includes_exam' => 'boolean',
        'includes_materials' => 'boolean',
        'is_active' => 'boolean',
        'features' => 'array',
    ];

    /**
     * Get the tenant that owns the package.
     */
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    /**
     * Get the student packages.
     */
    public function studentPackages(): HasMany
    {
        return $this->hasMany(StudentPackage::class);
    }

    /**
     * Scope a query to only include active packages.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to only include packages by license category.
     */
    public function scopeByLicenseCategory($query, $category)
    {
        return $query->where('license_category', $category);
    }

    /**
     * Scope a query to only include packages that include exam.
     */
    public function scopeWithExam($query)
    {
        return $query->where('includes_exam', true);
    }

    /**
     * Scope a query to only include packages that include materials.
     */
    public function scopeWithMaterials($query)
    {
        return $query->where('includes_materials', true);
    }

    /**
     * Check if package is active.
     */
    public function isActive(): bool
    {
        return $this->is_active;
    }

    /**
     * Check if package includes exam.
     */
    public function includesExam(): bool
    {
        return $this->includes_exam;
    }

    /**
     * Check if package includes materials.
     */
    public function includesMaterials(): bool
    {
        return $this->includes_materials;
    }

    /**
     * Get the total hours.
     */
    public function getTotalHoursAttribute(): int
    {
        return $this->theory_hours + $this->practical_hours;
    }

    /**
     * Get the formatted price.
     */
    public function getFormattedPriceAttribute(): string
    {
        return number_format($this->price, 2) . ' MAD';
    }

    /**
     * Get the price per hour.
     */
    public function getPricePerHourAttribute(): float
    {
        if ($this->total_hours == 0) return 0;
        return $this->price / $this->total_hours;
    }

    /**
     * Get the formatted price per hour.
     */
    public function getFormattedPricePerHourAttribute(): string
    {
        return number_format($this->price_per_hour, 2) . ' MAD/hour';
    }

    /**
     * Get the validity period in months.
     */
    public function getValidityMonthsAttribute(): float
    {
        return $this->validity_days / 30;
    }

    /**
     * Get the formatted validity period.
     */
    public function getFormattedValidityAttribute(): string
    {
        if ($this->validity_days >= 365) {
            $years = floor($this->validity_days / 365);
            $remainingDays = $this->validity_days % 365;
            $months = floor($remainingDays / 30);
            
            if ($months > 0) {
                return $years . ' year' . ($years > 1 ? 's' : '') . ' ' . $months . ' month' . ($months > 1 ? 's' : '');
            }
            
            return $years . ' year' . ($years > 1 ? 's' : '');
        } elseif ($this->validity_days >= 30) {
            $months = floor($this->validity_days / 30);
            $remainingDays = $this->validity_days % 30;
            
            if ($remainingDays > 0) {
                return $months . ' month' . ($months > 1 ? 's' : '') . ' ' . $remainingDays . ' day' . ($remainingDays > 1 ? 's' : '');
            }
            
            return $months . ' month' . ($months > 1 ? 's' : '');
        }
        
        return $this->validity_days . ' day' . ($this->validity_days > 1 ? 's' : '');
    }

    /**
     * Get the package summary.
     */
    public function getSummaryAttribute(): string
    {
        $summary = $this->name;
        
        if ($this->theory_hours > 0) {
            $summary .= ' - ' . $this->theory_hours . 'h theory';
        }
        
        if ($this->practical_hours > 0) {
            $summary .= ' - ' . $this->practical_hours . 'h practical';
        }
        
        if ($this->includes_exam) {
            $summary .= ' + Exam';
        }
        
        if ($this->includes_materials) {
            $summary .= ' + Materials';
        }
        
        return $summary;
    }

    /**
     * Get the package features as a list.
     */
    public function getFeaturesListAttribute(): array
    {
        $features = [];
        
        if ($this->theory_hours > 0) {
            $features[] = $this->theory_hours . ' hours of theory lessons';
        }
        
        if ($this->practical_hours > 0) {
            $features[] = $this->practical_hours . ' hours of practical lessons';
        }
        
        if ($this->includes_exam) {
            $features[] = 'Exam included';
        }
        
        if ($this->includes_materials) {
            $features[] = 'Learning materials included';
        }
        
        if ($this->features && is_array($this->features)) {
            $features = array_merge($features, $this->features);
        }
        
        return $features;
    }

    /**
     * Check if package is suitable for a license category.
     */
    public function isSuitableFor(string $licenseCategory): bool
    {
        return $this->license_category === $licenseCategory || $this->license_category === null;
    }

    /**
     * Get the number of students who purchased this package.
     */
    public function getStudentCountAttribute(): int
    {
        return $this->studentPackages()->count();
    }

    /**
     * Get the total revenue from this package.
     */
    public function getTotalRevenueAttribute(): float
    {
        return $this->studentPackages()->sum('price_paid');
    }
}
