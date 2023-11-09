<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Chatify\Traits\UUID;

class ChMessage extends Model
{
    use UUID;

    public function reactions(){
        return $this->hasMany(ChUserReaction::class);
    }
}
