<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class masterdiscounts extends Model
{
    use HasFactory;
    protected $table = 'masterdiscounts';
    protected $primaryKey = 'discountId';

   public function userid()
    {
        return $this->hasOne(User::class, 'id', 'userId');
    }
}
