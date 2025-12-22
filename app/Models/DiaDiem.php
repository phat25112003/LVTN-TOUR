<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DiaDiem extends Model
{
    use HasFactory;
    protected $table = 'diadiem';
    protected $primaryKey = 'maDiaDiem';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = true;
    protected $fillable = [
        'tenDiaDiem',
        'maDanhMuc',
    ];
    protected $casts = [
        'maDiaDiem'  => 'integer',
        'maDanhMuc'  => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
    public function danhMuc(): BelongsTo
    {
        return $this->belongsTo(DanhMuc::class, 'maDanhMuc', 'maDanhMuc');
    }
    public function scopeSearch($query, $keyword)
    {
        if ($keyword) {
            return $query->where('tenDiaDiem', 'like', "%{$keyword}%");
        }
        return $query;
    }
    public function getTenDanhMucAttribute(): string
    {
        return $this->danhMuc?->tenDanhMuc ?? '—';
    }
    public function setTenDiaDiemAttribute($value)
    {
        $this->attributes['tenDiaDiem'] = ucwords(strtolower(trim($value)));
    }
}