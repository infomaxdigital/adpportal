<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MydanceStyle extends Model
{
    use HasFactory;
    protected $table = 'mydancestyle';

    public function addedby(){
        return $this->hasOne(User::class,'id','added_by');
    }
    public function teacherId(){
        return $this->hasOne(User::class,'id','teacherId');
    }
}

