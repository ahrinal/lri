<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\LriType;
use Illuminate\Support\Facades\Auth;

class News extends Model
{
    use HasFactory;

    public static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->created_by = Auth::id();
            $model->updated_by = Auth::id();
        });

        static::updating(function ($model) {
            $model->updated_by = Auth::id();
        });
    }
    
    public function lri(){
        return $this->belongsTo(LriType::class, 'lri_type_id', 'id');
    }

    public function institution(){
        return $this->belongsTo(Institution::class, 'institution_id', 'id');
    }
}
