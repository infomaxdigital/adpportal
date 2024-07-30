<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassModel extends Model
{
    use HasFactory;
    protected $table = 'classes';
    protected $casts = [
        'danceStyleLevel' => 'array',
    ];

    public function addedby(){
        return $this->hasOne(User::class,'id','added_by');
    }
    public function teacher(){
        return $this->hasOne(User::class,'id','teacherId');
    }
    
}
