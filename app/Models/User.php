<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, HasRoles, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'division_id',
        'is_active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
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
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Relationships
     */

    /**
     * Get the division that the user belongs to
     */
    public function division()
    {
        return $this->belongsTo(Division::class);
    }

    /**
     * Get documents uploaded by this user
     */
    public function documents()
    {
        return $this->hasMany(Document::class, 'uploaded_by');
    }

    /**
     * Get document loans as borrower
     */
    public function documentLoans()
    {
        return $this->hasMany(DocumentLoan::class, 'borrower_id');
    }

    /**
     * Get document loans approved by this user
     */
    public function approvedLoans()
    {
        return $this->hasMany(DocumentLoan::class, 'approved_by');
    }

    /**
     * Get audit logs for this user
     */
    public function auditLogs()
    {
        return $this->hasMany(AuditLog::class);
    }

    /**
     * Helper Methods
     */

    /**
     * Check if user is admin
     */
    public function isAdmin(): bool
    {
        return $this->hasRole('admin');
    }

    /**
     * Check if user is staff (QA or Engineering)
     */
    public function isStaff(): bool
    {
        return $this->hasAnyRole(['qa_staff', 'engineering_staff']);
    }

    /**
     * Check if user can access document based on category
     */
    public function canAccessCategory(string $categoryCode): bool
    {
        // Admin can access all
        if ($this->isAdmin()) {
            return true;
        }

        // QA staff can access Quality documents
        if ($this->hasRole('qa_staff') && $categoryCode === 'QUALITY') {
            return true;
        }

        // Engineering staff can access Engineering documents
        if ($this->hasRole('engineering_staff') && $categoryCode === 'ENGINEERING') {
            return true;
        }

        // All users can access Certification documents
        if ($categoryCode === 'CERTIFICATION') {
            return true;
        }

        return false;
    }

    /**
     * Scopes
     */

    /**
     * Scope to get only active users
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to filter by division
     */
    public function scopeByDivision($query, $divisionId)
    {
        return $query->where('division_id', $divisionId);
    }

    /**
     * Scope to filter by role
     */
    public function scopeByRole($query, $roleName)
    {
        return $query->role($roleName);
    }
}
