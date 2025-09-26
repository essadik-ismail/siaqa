<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class Client extends Model
{
    use HasFactory;

    protected $table = 'clients';

    protected $fillable = [
        'tenant_id',
        'user_id',
        'student_number',
        'name',
        'name_ar',
        'email',
        'phone',
        'cin',
        'birth_date',
        'birth_place',
        'address',
        'reference',
        'cinimage',
        'image',
        'emergency_contact_name',
        'emergency_contact_phone',
        'license_category',
        'status',
        'registration_date',
        'theory_hours_completed',
        'practical_hours_completed',
        'required_theory_hours',
        'required_practical_hours',
        'total_paid',
        'total_due',
        'progress_skills',
        'notes',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'registration_date' => 'date',
        'theory_hours_completed' => 'integer',
        'practical_hours_completed' => 'integer',
        'required_theory_hours' => 'integer',
        'required_practical_hours' => 'integer',
        'total_paid' => 'decimal:2',
        'total_due' => 'decimal:2',
        'progress_skills' => 'array',
    ];

    /**
     * Get the tenant that owns the student.
     */
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    /**
     * Get the user account for the student.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the lessons for the student.
     */
    public function lessons(): HasMany
    {
        return $this->hasMany(Lesson::class, 'student_id');
    }

    /**
     * Get the exams for the student.
     */
    public function exams(): HasMany
    {
        return $this->hasMany(Exam::class, 'student_id');
    }

    /**
     * Get the payments for the student.
     */
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class, 'student_id');
    }

    /**
     * Get the progress records for the student.
     */
    public function progress(): HasMany
    {
        return $this->hasMany(StudentProgress::class, 'student_id');
    }

    /**
     * Get the student packages.
     */
    public function studentPackages(): HasMany
    {
        return $this->hasMany(StudentPackage::class, 'student_id');
    }

    /**
     * Get the full name of the student.
     */
    public function getFullNameAttribute(): string
    {
        return $this->name ? trim($this->name) : '';
    }

    /**
     * Get the full name in Arabic.
     */
    public function getFullNameArAttribute(): string
    {
        return $this->name_ar ? trim($this->name_ar) : '';
    }

    /**
     * Scope a query to only include active students.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope a query to only include registered students.
     */
    public function scopeRegistered($query)
    {
        return $query->where('status', 'registered');
    }

    /**
     * Scope a query to only include graduated students.
     */
    public function scopeGraduated($query)
    {
        return $query->where('status', 'graduated');
    }

    /**
     * Check if student is active.
     */
    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    /**
     * Check if student is graduated.
     */
    public function isGraduated(): bool
    {
        return $this->status === 'graduated';
    }

    /**
     * Check if student is suspended.
     */
    public function isSuspended(): bool
    {
        return $this->status === 'suspended';
    }

    /**
     * Get completion percentage for theory hours.
     */
    public function getTheoryCompletionPercentageAttribute(): float
    {
        if ($this->required_theory_hours == 0) return 0;
        return ($this->theory_hours_completed / $this->required_theory_hours) * 100;
    }

    /**
     * Get completion percentage for practical hours.
     */
    public function getPracticalCompletionPercentageAttribute(): float
    {
        if ($this->required_practical_hours == 0) return 0;
        return ($this->practical_hours_completed / $this->required_practical_hours) * 100;
    }

    /**
     * Check if student has completed required hours.
     */
    public function hasCompletedRequiredHours(): bool
    {
        return $this->theory_hours_completed >= $this->required_theory_hours &&
               $this->practical_hours_completed >= $this->required_practical_hours;
    }

    /**
     * Get the main image URL.
     */
    public function getImageUrlAttribute(): ?string
    {
        if ($this->image) {
            return Storage::disk('public')->url($this->image);
        }
        return null;
    }

    /**
     * Get the additional images URLs.
     */
    public function getImagesUrlsAttribute(): array
    {
        if ($this->images && is_array($this->images)) {
            return array_map(function ($image) {
                return Storage::disk('public')->url($image);
            }, $this->images);
        }
        return [];
    }

    /**
     * Get the document URL.
     */
    public function getDocumentUrlAttribute(): ?string
    {
        if ($this->document) {
            return Storage::disk('public')->url($this->document);
        }
        return null;
    }

    /**
     * Check if client has images.
     */
    public function hasImages(): bool
    {
        return !empty($this->images) || !empty($this->image);
    }

    /**
     * Get identity document type label.
     */
    public function getIdentityDocumentTypeLabelAttribute(): string
    {
        $types = [
            'carte_nationale' => 'Carte Nationale',
            'passeport' => 'Passeport',
            'permis_conduire' => 'Permis de Conduire',
            'carte_sejour' => 'Carte de Séjour',
        ];

        return $types[$this->type_piece_identite] ?? $this->type_piece_identite;
    }
}
