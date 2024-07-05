<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class mastermemberships extends Model
{
    use HasFactory;
    protected $table = 'mastermemberships';
    protected $primaryKey = 'membershipId';

   public function userid()
    {
        return $this->hasOne(User::class, 'id', 'userId');
    }
}
