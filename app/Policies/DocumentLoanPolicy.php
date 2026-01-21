<?php

namespace App\Policies;

use App\Models\DocumentLoan;
use App\Models\User;

class DocumentLoanPolicy
{
    /**
     * Determine if user can view any loans
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view loans');
    }

    /**
     * Determine if user can view specific loan
     */
    public function view(User $user, DocumentLoan $loan): bool
    {
        // Admin can view all
        if ($user->hasRole('admin')) {
            return true;
        }

        // Borrower can view their own loans
        if ($loan->borrower_id === $user->id) {
            return true;
        }

        // Approver can view loans they approved
        if ($loan->approved_by === $user->id) {
            return true;
        }

        // Staff can view loans for their category documents
        if ($user->isStaff()) {
            $categoryCode = $loan->document->category->code;

            if ($user->hasRole('qa_staff') && $categoryCode === 'QUALITY') {
                return true;
            }

            if ($user->hasRole('engineering_staff') && $categoryCode === 'ENGINEERING') {
                return true;
            }
        }

        return false;
    }

    /**
     * Determine if user can request loan
     */
    public function create(User $user): bool
    {
        return $user->can('request loan');
    }

    /**
     * Determine if user can approve loan
     */
    public function approve(User $user, DocumentLoan $loan): bool
    {
        // Must have permission
        if (! $user->can('approve loan')) {
            return false;
        }

        // Loan must be pending
        if ($loan->status !== 'pending') {
            return false;
        }

        // Admin can approve all
        if ($user->hasRole('admin')) {
            return true;
        }

        // Staff can approve loans for their category
        $categoryCode = $loan->document->category->code;

        if ($user->hasRole('qa_staff') && $categoryCode === 'QUALITY') {
            return true;
        }

        if ($user->hasRole('engineering_staff') && $categoryCode === 'ENGINEERING') {
            return true;
        }

        return false;
    }

    /**
     * Determine if user can reject loan
     */
    public function reject(User $user, DocumentLoan $loan): bool
    {
        // Same logic as approve
        return $this->approve($user, $loan);
    }

    /**
     * Determine if user can return document
     */
    public function return(User $user, DocumentLoan $loan): bool
    {
        // Loan must be approved
        if ($loan->status !== 'approved') {
            return false;
        }

        // Borrower can return their own loan
        if ($loan->borrower_id === $user->id) {
            return true;
        }

        // Admin can mark as returned
        if ($user->hasRole('admin')) {
            return true;
        }

        // Staff can mark as returned for their category
        $categoryCode = $loan->document->category->code;

        if ($user->hasRole('qa_staff') && $categoryCode === 'QUALITY') {
            return true;
        }

        if ($user->hasRole('engineering_staff') && $categoryCode === 'ENGINEERING') {
            return true;
        }

        return false;
    }

    /**
     * Determine if user can cancel loan
     */
    public function cancel(User $user, DocumentLoan $loan): bool
    {
        // Can only cancel pending loans
        if ($loan->status !== 'pending') {
            return false;
        }

        // Borrower can cancel their own request
        if ($loan->borrower_id === $user->id) {
            return true;
        }

        // Admin can cancel any
        return $user->hasRole('admin');
    }

    /**
     * Determine if user can delete loan record
     */
    public function delete(User $user, DocumentLoan $loan): bool
    {
        // Only admin can delete loan records
        return $user->hasRole('admin');
    }
}
