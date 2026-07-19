<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Crop extends Model
{
    protected $table = 'crops';

    protected $fillable = [
        'name',
        'location',
        'description',
        'is_active'
    ];

    /**
     * Relación muchos a muchos con usuarios
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_crop', 'crop_id', 'user_id');
    }

    /**
     * Sensores del invernadero
     */
    public function sensors(): HasMany
    {
        return $this->hasMany(Sensor::class, 'crop_id');
    }
}
