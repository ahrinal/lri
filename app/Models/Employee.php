<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Employee extends Model
{
    use HasFactory;
    public $additional_attributes = ['full_name', 'tanggal_lahir'];

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
    
    public function getMemberBrowseAttribute()
    {
        return $this->getFullNameAttribute();
    }
    
    public function getFullNameAttribute()
    {
        return "{$this->first_title} {$this->first_name} {$this->last_name} {$this->end_title}";
    }

    public function getTanggalLahirAttribute()
    {
        return "{$this->born_place}, {$this->born_date}";
    }

    
}
