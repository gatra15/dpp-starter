<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo; // <-- PASTIKAN INI DI-IMPORT

class AuditTrail extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    protected $table = 'audit_trails';
    public $timestamps = false;

    // Pastikan kolom-kolom ini ada di migrasi Anda dan sudah diisi oleh LogAction
    protected $fillable = [
        'entity_type',
        'entity_id',
        'action',
        'performed_by', // <-- Pastikan kolom ini ada di tabel audit_trails
        'datetime',
    ];

    // --- TAMBAHKAN RELASI INI ---
    /**
     * Get the user who performed the action.
     */
    public function performedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'performed_by'); // 'performed_by' adalah foreign key ke tabel users
    }
}
