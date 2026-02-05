<?php

namespace App\Models;

use App\Enums\LoanStatus;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentLoan extends Model
{
    use HasFactory;

    protected $fillable = [
        'document_id',
        'borrower_id',
        'approved_by',
        'loan_date',
        'due_date',
        'return_date',
        'status',
        'purpose',
        'notes',
        'rejection_reason',
    ];

    protected $casts = [
        'loan_date' => 'date',
        'due_date' => 'date',
        'return_date' => 'date',
        'status' => LoanStatus::class,
    ];

    /**
     * Get the document being loaned
     */
    public function document()
    {
        return $this->belongsTo(Document::class);
    }

    /**
     * Get the borrower
     */
    public function borrower()
    {
        return $this->belongsTo(User::class, 'borrower_id');
    }

    /**
     * Get the approver
     */
    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Check if loan is overdue
     */
    public function isOverdue()
    {
        return $this->status === 'approved'
            && ! $this->return_date
            && Carbon::parse($this->due_date)->isPast();
    }

    /**
     * Get status badge color
     */
    public function getStatusBadgeColorAttribute()
    {
        return match ($this->status) {
            'pending' => 'yellow',
            'approved' => 'green',
            'rejected' => 'red',
            'returned' => 'blue',
            default => 'gray',
        };
    }

    /**
     * Get status label
     */
    public function getStatusLabelAttribute()
    {
        return match ($this->status) {
            'pending' => 'Menunggu Persetujuan',
            'approved' => 'Disetujui',
            'rejected' => 'Ditolak',
            'returned' => 'Dikembalikan',
            default => 'Unknown',
        };
    }

    /**
     * Scope: Pending loans
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope: Approved loans
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Scope: Active loans (approved but not returned)
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'approved')
            ->whereNull('return_date');
    }

    /**
     * Scope: Overdue loans
     */
    public function scopeOverdue($query)
    {
        return $query->where('status', 'approved')
            ->whereNull('return_date')
            ->where('due_date', '<', Carbon::now());
    }

    /**
     * Scope: By borrower
     */
    public function scopeByBorrower($query, $userId)
    {
        return $query->where('borrower_id', $userId);
    }
}
