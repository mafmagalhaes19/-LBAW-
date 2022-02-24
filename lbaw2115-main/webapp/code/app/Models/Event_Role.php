<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event_Role extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'event_role';


    public function event()
    {
        return $this->belongsTo('App\Models\Event');
    }

    public function user()
    {

        return $this->belongsTo('App\Models\User');
    }


    public function hosts(){
        return $this->belongsToMany('App\Event_Role', 'event_role');
    }

}
