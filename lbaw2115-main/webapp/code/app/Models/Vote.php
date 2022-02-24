<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vote extends Model
{
    use HasFactory;

    protected $table = "vote";


    public $timestamps = false;



    public function event()
    {
        return $this->belongsTo('App\Models\Event_Role', 'event_roleid');
    }



    public function comment()
    {
        return $this->belongsTo('App\Models\Comment', 'commentid');
    }



    public function announcement()
    {
        return $this->belongsTo('App\Models\Announcement', 'announcementid');
    }

}
