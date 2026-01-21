<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Document extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'description',
        'category_id',
        'division_id',
        'current_version',
        'file_path',
        'file_name',
        'file_size',
        'mime_type',
        'uploaded_by',
    ];

    /**
     * Get the attributes that should be cast.
     * Laravel 12 uses method-based casts
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'file_size' => 'integer',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
            'deleted_at' => 'datetime',
        ];
    }

    /**
     * Relationships
     */

    /**
     * Get the category of the document
     */
    public function category()
    {
        return $this->belongsTo(DocumentCategory::class);
    }

    /**
     * Get the division that owns the document
     */
    public function division()
    {
        return $this->belongsTo(Division::class);
    }

    /**
     * Get the user who uploaded the document
     */
    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    /**
     * Get all versions of this document
     */
    public function versions()
    {
        return $this->hasMany(DocumentVersion::class)->orderByDesc('created_at');
    }

    /**
     * Get document loans
     */
    public function loans()
    {
        return $this->hasMany(DocumentLoan::class)->orderByDesc('created_at');
    }

    /**
     * Get active loan (not returned yet)
     */
    public function activeLoan()
    {
        return $this->hasOne(DocumentLoan::class)
            ->whereIn('status', ['pending', 'approved'])
            ->whereNull('return_date')
            ->latest();
    }

    /**
     * Helper Methods
     */

    /**
     * Check if document is currently on loan
     */
    public function isOnLoan(): bool
    {
        return $this->activeLoan()->exists();
    }

    /**
     * Get formatted file size
     */
    public function getFormattedFileSizeAttribute(): string
    {
        $bytes = $this->file_size;
        $units = ['B', 'KB', 'MB', 'GB'];

        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, 2).' '.$units[$i];
    }

    /**
     * Get file extension
     */
    public function getFileExtensionAttribute(): string
    {
        return strtoupper(pathinfo($this->file_name, PATHINFO_EXTENSION));
    }

    /**
     * Get file icon based on mime type
     */
    public function getFileIconAttribute(): string
    {
        return match (true) {
            str_contains($this->mime_type, 'pdf') => 'pdf',
            str_contains($this->mime_type, 'word') || str_contains($this->mime_type, 'document') => 'doc',
            str_contains($this->mime_type, 'excel') || str_contains($this->mime_type, 'spreadsheet') => 'xls',
            str_contains($this->mime_type, 'powerpoint') || str_contains($this->mime_type, 'presentation') => 'ppt',
            str_contains($this->mime_type, 'image') => 'image',
            default => 'file',
        };
    }

    /**
     * Check if document can be previewed in browser
     */
    public function isPreviewable(): bool
    {
        $previewableTypes = ['application/pdf', 'image/jpeg', 'image/png', 'image/gif'];

        return in_array($this->mime_type, $previewableTypes);
    }

    /**
     * Scopes
     */

    /**
     * Scope: Filter by category
     */
    public function scopeByCategory($query, $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }

    /**
     * Scope: Filter by division
     */
    public function scopeByDivision($query, $divisionId)
    {
        return $query->where('division_id', $divisionId);
    }

    /**
     * Scope: Search by title, description, or filename
     */
    public function scopeSearch($query, $search)
    {
        if (empty($search)) {
            return $query;
        }

        return $query->where(function ($q) use ($search) {
            $q->where('title', 'ILIKE', "%{$search}%")
                ->orWhere('description', 'ILIKE', "%{$search}%")
                ->orWhere('file_name', 'ILIKE', "%{$search}%");
        });
    }

    /**
     * Scope: Filter by uploader
     */
    public function scopeByUploader($query, $userId)
    {
        return $query->where('uploaded_by', $userId);
    }

    /**
     * Scope: Get documents user can access
     */
    public function scopeAccessibleBy($query, User $user)
    {
        // Admin can access all
        if ($user->hasRole('admin')) {
            return $query;
        }

        return $query->where(function ($q) use ($user) {
            // User's division documents
            $q->where('division_id', $user->division_id);

            // QA staff can access Quality documents
            if ($user->hasRole('qa_staff')) {
                $q->orWhereHas('category', function ($cat) {
                    $cat->where('code', 'QUALITY');
                });
            }

            // Engineering staff can access Engineering documents
            if ($user->hasRole('engineering_staff')) {
                $q->orWhereHas('category', function ($cat) {
                    $cat->where('code', 'ENGINEERING');
                });
            }

            // All users can access Certification documents
            $q->orWhereHas('category', function ($cat) {
                $cat->where('code', 'CERTIFICATION');
            });
        });
    }

    /**
     * Scope: Recently uploaded
     */
    public function scopeRecent($query, $days = 7)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    /**
     * Scope: On loan
     */
    public function scopeOnLoan($query)
    {
        return $query->whereHas('activeLoan');
    }

    /**
     * Scope: Available (not on loan)
     */
    public function scopeAvailable($query)
    {
        return $query->whereDoesntHave('activeLoan');
    }
}
