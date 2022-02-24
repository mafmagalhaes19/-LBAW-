<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $table = "eventg";

    public $timestamps  = false;


    protected $fillable = [
        'eventname', 'startdate', 'enddate', 'place','duration','state','isprivate'
    ];

    
    protected $attributes = [
        'isprivate' => false,
    ];

    public function announcements($event_id)
    {
        $announcements = Event_Role::where('eventid', $event_id)->join('event_announcement', 'event_role.id', '=', 'event_announcement.role_id')->get()->unique();
        return $announcements;
    }


    public function comments($event_id)
    {
        $comments = Event_Role::where('eventid', $event_id)->join('event_comment', 'event_role.id', '=', 'event_comment.role_id')->get()->unique();
        return $comments;
    }

    public function polls($event_id)
    {
        $polls = Event_Role::where('eventid', $event_id)->join('event_poll', 'event_role.id', '=', 'event_poll.role_id')->get()->unique();
        return $polls;
    }

    public function pollOptions($event_id)
    {
        $pollOptions = PollOption::all();
        return $pollOptions;
    }


    public function participants($event_id)
    {
        $participants = Event_Role::where('ishost',false)->where('eventid', $event_id)->join('users', 'event_role.userid', '=', 'users.id')->get()->unique();
        return $participants;
    }


    public function hosts($event_id)
    {
        $hosts = Event_Role::where('ishost', true)->where('eventid', $event_id)->join('users', 'event_role.userid', '=', 'users.id')->get()->unique();
        return $hosts;
    }

    public function tag($event_id){
        $event = Event::find($event_id);
        $tag = Tag::find($event->tagid);
        return $tag;
    }

   
}
