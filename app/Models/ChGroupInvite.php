<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChGroupInvite extends Model
{
    use HasFactory;

    protected $table = 'ch_group_invites';

    public function chatGroup(){
        return $this->belongsTo(ChGroup::class);
    }
}
