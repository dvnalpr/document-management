<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Division extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'description',
    ];

    /**
     * Get users in this division
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }

    /**
     * Get documents belonging to this division
     */
    public function documents()
    {
        return $this->hasMany(Document::class);
    }
}
