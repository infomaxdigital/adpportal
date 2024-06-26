<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class masterdancelevel extends Model
{
    use HasFactory;
    protected $table = 'masterdancelevel';
    protected $primaryKey = 'dancelevelId';

    public function addedby(){
        return $this->hasOne(User::class,'id','added_by');
    }

    public function lastupdatedby(){
        return $this->hasOne(User::class,'id','last_updated_by');
    }
}
