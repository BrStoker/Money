<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class ChGroup extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'ch_groups';

    public function chatUsers(){
        return $this->hasMany(ChGroupUsers::class);
    }

    public function messages(){
        return $this->hasMany(ChGroupMessages::class);
    }

    public function invites(){
        return $this->hasMany(ChGroupInvite::class);
    }

    public function settings(){
        return $this->hasMany(ChGroupSettings::class);
    }
}
