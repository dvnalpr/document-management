<?php

namespace App\Policies;

use App\Models\Document;
use App\Models\User;

class DocumentPolicy
{
    /**
     * Determine if user can view any documents
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view documents');
    }

    /**
     * Determine if user can view specific document
     */
    public function view(User $user, Document $document): bool
    {
        // Must have permission first
        if (! $user->can('view documents')) {
            return false;
        }

        // Admin can view all
        if ($user->hasRole('admin')) {
            return true;
        }

        // Check category-based access
        $categoryCode = $document->category->code;

        // QA staff can view Quality documents
        if ($user->hasRole('qa_staff') && $categoryCode === 'QUALITY') {
            return true;
        }

        // Engineering staff can view Engineering documents
        if ($user->hasRole('engineering_staff') && $categoryCode === 'ENGINEERING') {
            return true;
        }

        // All users can view Certification documents
        if ($categoryCode === 'CERTIFICATION') {
            return true;
        }

        // Users can view documents from their own division
        if ($user->division_id === $document->division_id) {
            return true;
        }

        return false;
    }

    /**
     * Determine if user can create documents
     */
    public function create(User $user): bool
    {
        return $user->can('create documents');
    }

    /**
     * Determine if user can update document
     */
    public function update(User $user, Document $document): bool
    {
        // Must have permission first
        if (! $user->can('edit documents')) {
            return false;
        }

        // Admin can edit all
        if ($user->hasRole('admin')) {
            return true;
        }

        // Users can only edit their own documents
        if ($document->uploaded_by === $user->id) {
            return true;
        }

        // Staff can edit documents in their category
        if ($user->hasRole('qa_staff') && $document->category->code === 'QUALITY') {
            return true;
        }

        if ($user->hasRole('engineering_staff') && $document->category->code === 'ENGINEERING') {
            return true;
        }

        return false;
    }

    /**
     * Determine if user can delete document
     */
    public function delete(User $user, Document $document): bool
    {
        // Only admin can delete
        if ($user->hasRole('admin')) {
            return true;
        }

        // Or the uploader can delete their own document
        if ($document->uploaded_by === $user->id && $user->can('delete documents')) {
            return true;
        }

        return false;
    }

    /**
     * Determine if user can download document
     */
    public function download(User $user, Document $document): bool
    {
        // Must be able to view first
        if (! $this->view($user, $document)) {
            return false;
        }

        // Check download permission
        return $user->can('download documents');
    }

    /**
     * Determine if user can restore deleted document
     */
    public function restore(User $user, Document $document): bool
    {
        return $user->hasRole('admin');
    }

    /**
     * Determine if user can permanently delete document
     */
    public function forceDelete(User $user, Document $document): bool
    {
        return $user->hasRole('admin');
    }
}
