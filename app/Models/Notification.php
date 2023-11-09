<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Notification extends Model
{
    use CrudTrait;
    use HasFactory;
    //use SoftDeletes;
    use \App\Traits\Model;

    protected $table = 'notification';

    public function notificationEntiry() {
        return $this->hasMany(NotificationEntity::class);
    }


}
