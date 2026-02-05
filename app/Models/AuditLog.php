<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'action',
        'model_type',
        'model_id',
        'old_values',
        'new_values',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
    ];

    /**
     * Get the user who performed the action
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the model that was affected
     */
    public function model()
    {
        if ($this->model_type && $this->model_id) {
            return $this->morphTo('model', 'model_type', 'model_id');
        }

        return null;
    }

    /**
     * Get action label
     */
    public function getActionLabelAttribute()
    {
        return match ($this->action) {
            'login' => 'Login',
            'logout' => 'Logout',
            'upload' => 'Upload Dokumen',
            'download' => 'Download Dokumen',
            'view' => 'Lihat Dokumen',
            'edit' => 'Edit Dokumen',
            'delete' => 'Hapus Dokumen',
            'create_user' => 'Buat User',
            'update_user' => 'Update User',
            'delete_user' => 'Hapus User',
            'approve_loan' => 'Setujui Peminjaman',
            'reject_loan' => 'Tolak Peminjaman',
            'request_loan' => 'Request Peminjaman',
            'return_loan' => 'Kembalikan Dokumen',
            default => ucfirst($this->action),
        };
    }

    /**
     * Scope: Filter by action
     */
    public function scopeByAction($query, $action)
    {
        return $query->where('action', $action);
    }

    /**
     * Scope: Filter by user
     */
    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope: Filter by date range
     */
    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('created_at', [$startDate, $endDate]);
    }

    /**
     * Scope: Filter by model type
     */
    public function scopeByModelType($query, $modelType)
    {
        return $query->where('model_type', $modelType);
    }

    public function prunable()
    {
        return static::where('created_at', '<=', now()->subYear());
    }
}
