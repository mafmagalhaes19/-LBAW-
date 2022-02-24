<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\User;
use App\Models\Event_Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PollEventController extends Controller
{


    public function index($event_id, $poll)
    {
        return view('pages.pollevent', ['event_id' => $event_id]);
    }

    public function poll(Request $request, $event_id)
    {
        $poll = new Poll;
        $role = Event_Role::where('ishost', true)->where('eventid', $event_id)->where('userid', Auth::user()->id)->get()->first();
        $poll->role_id = $role->id;
        $poll->messagep = $request->poll_message;
        $poll->save();

        return redirect()->route('event.show', ['event_id' => $event_id]);
    }

}
