<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class LriMember extends Model
{
    // use HasFactory;

    protected $table = "lri_member";
    public $additional_attributes = ['full_name'];
    
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
}
