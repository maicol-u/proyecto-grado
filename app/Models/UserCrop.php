<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserCrop extends Model
{

    protected $table = 'user_crop';

    protected $fillable = [
        'user_id',
        'crop_id',
    ];
}
