<?php

namespace App\Models;

use App\Scopes\VerifiedScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Votes extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'status',
        'verified_by',
        'verified_at',
        'created_by',
        'kecamatan_id',
        'kelurahan_id',
        'tps_id',
        'paslon_1_vote',
        'paslon_2_vote',
        'paslon_3_vote',
        'foto_c1_plano',
    ];

    /**
     * Sortir table list vote by status verified/unverified 
     */
    protected static function booted()
    {
        static::addGlobalScope(new VerifiedScope);

    }

    /**
     * munculkan nama user di kolom Operator
     */    
    public function operator()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    /**
     * munculkan nama user di kolom Saksi
     */     
    public function saksi()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function kecamatan()
    {
        return $this->belongsTo(Kecamatan::class);
    }

    public function kelurahan()
    {
        return $this->belongsTo(Kelurahan::class);
    }

    public function tps()
    {
        return $this->belongsTo(Tps::class);
    }       
    
    /**
     * Menampilkan pesan error debug
     */
    // protected static function boot()
    // {
    //     parent::boot();
    
    //     static::saving(function ($model) {
    //         if (!is_numeric($model->paslon_1_vote) || $model->paslon_2_vote || $model->paslon_3_vote < 0) {
    //             throw new \Exception('Perolehan suara harus berupa angka positif.');
    //         }
    //     });
    // }
        
}
