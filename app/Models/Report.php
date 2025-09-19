<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Report extends Model
{
    use HasFactory;

    protected $table = 'reports';

    protected $fillable = [
        'tenant_id',
        'created_by',
        'name',
        'description',
        'report_type',
        'filters',
        'data',
        'status',
        'file_path',
        'generated_at',
    ];

    protected $casts = [
        'filters' => 'array',
        'data' => 'array',
        'generated_at' => 'datetime',
    ];

    /**
     * Get the tenant that owns the report.
     */
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    /**
     * Get the agency for the report.
     */
    public function agence(): BelongsTo
    {
        return $this->belongsTo(Agence::class);
    }

    /**
     * Get the user who created the report.
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Scope a query to only include generating reports.
     */
    public function scopeGenerating($query)
    {
        return $query->where('status', 'generating');
    }

    /**
     * Scope a query to only include completed reports.
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Scope a query to only include failed reports.
     */
    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    /**
     * Scope a query to only include reports by type.
     */
    public function scopeByType($query, $type)
    {
        return $query->where('report_type', $type);
    }

    /**
     * Scope a query to only include reports for a specific agency.
     */
    public function scopeForAgency($query, $agencyId)
    {
        return $query->where('agence_id', $agencyId);
    }

    /**
     * Check if report is generating.
     */
    public function isGenerating(): bool
    {
        return $this->status === 'generating';
    }

    /**
     * Check if report is completed.
     */
    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    /**
     * Check if report is failed.
     */
    public function isFailed(): bool
    {
        return $this->status === 'failed';
    }

    /**
     * Get the report type label.
     */
    public function getReportTypeLabelAttribute(): string
    {
        $types = [
            'revenue' => 'Revenue Report',
            'student_progress' => 'Student Progress Report',
            'instructor_performance' => 'Instructor Performance Report',
            'vehicle_usage' => 'Vehicle Usage Report',
            'exam_results' => 'Exam Results Report',
            'custom' => 'Custom Report',
        ];

        return $types[$this->report_type] ?? $this->report_type;
    }

    /**
     * Get the status label.
     */
    public function getStatusLabelAttribute(): string
    {
        $statuses = [
            'generating' => 'Generating',
            'completed' => 'Completed',
            'failed' => 'Failed',
        ];

        return $statuses[$this->status] ?? $this->status;
    }

    /**
     * Get the status color for display.
     */
    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'generating' => 'blue',
            'completed' => 'green',
            'failed' => 'red',
            default => 'gray'
        };
    }

    /**
     * Get the formatted generated time.
     */
    public function getFormattedGeneratedAtAttribute(): ?string
    {
        return $this->generated_at ? $this->generated_at->format('M d, Y H:i') : null;
    }

    /**
     * Get the file URL.
     */
    public function getFileUrlAttribute(): ?string
    {
        if ($this->file_path && file_exists(storage_path('app/' . $this->file_path))) {
            return asset('storage/' . $this->file_path);
        }
        return null;
    }

    /**
     * Check if report has file.
     */
    public function hasFile(): bool
    {
        return $this->file_path && file_exists(storage_path('app/' . $this->file_path));
    }

    /**
     * Get the file size.
     */
    public function getFileSizeAttribute(): ?string
    {
        if (!$this->hasFile()) {
            return null;
        }

        $bytes = filesize(storage_path('app/' . $this->file_path));
        $units = ['B', 'KB', 'MB', 'GB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, 2) . ' ' . $units[$i];
    }

    /**
     * Mark report as completed.
     */
    public function markAsCompleted(string $filePath = null): void
    {
        $this->update([
            'status' => 'completed',
            'file_path' => $filePath,
            'generated_at' => now(),
        ]);
    }

    /**
     * Mark report as failed.
     */
    public function markAsFailed(): void
    {
        $this->update([
            'status' => 'failed',
            'generated_at' => now(),
        ]);
    }

    /**
     * Get the report data as array.
     */
    public function getDataArrayAttribute(): array
    {
        return $this->data ?? [];
    }

    /**
     * Get the filters as array.
     */
    public function getFiltersArrayAttribute(): array
    {
        return $this->filters ?? [];
    }

    /**
     * Check if report is downloadable.
     */
    public function isDownloadable(): bool
    {
        return $this->isCompleted() && $this->hasFile();
    }

    /**
     * Get the report summary.
     */
    public function getSummaryAttribute(): string
    {
        $summary = $this->name;
        
        if ($this->agence) {
            $summary .= ' - ' . $this->agence->nom_agence;
        }
        
        $summary .= ' (' . $this->report_type_label . ')';
        
        return $summary;
    }
}
