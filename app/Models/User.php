<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use CrudTrait, HasApiTokens, HasFactory, Notifiable;
    use SoftDeletes;
    use \App\Traits\Model;

    protected $table = 'users';

    protected $casts = [ 'last_online_at' => 'datetime' ];

    public function scores()
    {
        return $this->hasMany(UserScore::class);
    }

    public function subscribers()
    {
        return $this->hasMany(UserSubscribe::class);
    }

    public function viewed()
    {
        return $this->hasMany(UserView::class);
    }

    public function values()
    {
        return $this->hasMany(UserValue::class);
    }

    public function referals()
    {
        return $this->hasMany(UserReferal::class);
    }

    public function chatFolders()
    {
        return $this->hasMany(ChFolders::class);
    }

    public function chatBlockedUsers()
    {
        return $this->hasMany(ChBlockedUsers::class);
    }

    public function chatSettings()
    {
        return $this->hasMany(ChUsersSettings::class);
    }

    public function groupChat(){

        return $this->hasMany(ChGroupUsers::class);


    }


    public function chatGroupInvites(){
        return $this->hasMany(ChGroupInvite::class);
    }
    protected function getFioAttribute() {

        return implode(' ', [
            $this->first_name,
            $this->last_name,
            $this->second_name,
        ]);
        
    }

    public function setImageAttribute($value)
    {
        
        if($value)
        {

            $attribute_name = "image";

            $disk = "public";

            $destination_path = "images";

            $this->uploadFileToDisk($value, $attribute_name, $disk, $destination_path);

        }

    }

    public static function boot()
    {
        parent::boot();

        #static::created(function($obj) {

        #    if(!$obj->password)
        #        $obj->password = Hash::make(uniqid());

        #});

        #static::updating(function($obj) {

        #    if($obj->password)
        #        $obj->password = Hash::make($obj->password);

        #});

        static::deleting(function($obj) {

            \Storage::disk('public')->delete($obj->image);


        });
    }
}
