<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentLoan extends Model
{
    protected $fillable = [
        'document_id', 'user_id', 'token', 'duration',
        'request_date', 'approved_at', 'returned_at',
        'status', 'note', 'admin_note',
    ];

    protected $casts = [
        'request_date' => 'datetime',
        'approved_at' => 'datetime',
        'returned_at' => 'datetime',
    ];

    public function document()
    {
        return $this->belongsTo(Document::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
