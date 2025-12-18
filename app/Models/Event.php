<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function penyelenggara()
    {
        return $this->belongsTo(Penyelenggara::class, 'penyelenggara_id');
    }

    public function category()
    {
        return $this->belongsTo(Kategori::class);
    }

    // Relasi: Event di-approve oleh Admin (Optional)
    public function approver()
    {
        return $this->belongsTo(Admin::class, 'approved_by');
    }
}
