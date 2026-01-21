<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentVersion extends Model
{
    use HasFactory;

    protected $fillable = [
        'document_id',
        'version_number',
        'file_path',
        'file_name',
        'change_notes',
        'uploaded_by',
    ];

    /**
     * Get the document this version belongs to
     */
    public function document()
    {
        return $this->belongsTo(Document::class);
    }

    /**
     * Get the user who uploaded this version
     */
    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}
