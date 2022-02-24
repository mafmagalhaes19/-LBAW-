<?php

namespace App\Http\Controllers;

use App\Models\Invite;
use App\Models\User;
use App\Models\Event;
use App\Models\Event_Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InviteController extends Controller
{
    public function showusers(Request $request, $event_id)
    {
        $event = Event::find($event_id);


        if ($request->search_query == "Null") {
            $user_query = User::all();
        } else {
            $user_query = User::where('username', 'ilike', '%' . $request->search_query . '%');
        }
        $users = $user_query->get();


        return view('pages.invite', ['users' => $users, 'event' => $event]);
    }


    public function invite($event_id, $user_id)
    {
        $test_invite = Invite::where('receiverid', $user_id)->where('eventid', $event_id)->get();
        // If the user was already invited to this event
        if(count($test_invite) != 0){
            return redirect()->route('event.show', [ 'event_id' =>$event_id,
            'popup_message' => 'Error: That user has already been invited to this event.']);
        }

        $invite = new Invite;
        $invite->receiverid = $user_id;
        $receiver = User::find($user_id);
        foreach ($receiver->events_as_participant($user_id) as $event) {
            if ($event->id == $event_id)
                abort(403, "User cannot be invited");
        }
        $invite->senderid = Auth::user()->id;
        $invite->eventid = $event_id;
        try {
            $invite->save();
        } catch (\Illuminate\Database\QueryException $e) {
            return abort(403, "Duplicate found");
        }
        return redirect()->route('event.show', [ 'event_id' =>$event_id,
        'popup_message' => 'User successfully invited.']);
    }

    public function accept($user_id, $invite_id){

        $invite = Invite::find($invite_id);
        $user = User::find($invite->senderid);
        $event = Event::find($invite->eventid);


        $test_event_role = Event_Role::where('userid', $user_id)->where('eventid', $invite->eventid)->get();
        // If the user was already participating in this event
        if(count($test_event_role) != 0){
            return redirect()->route('event.show', [ 'event_id' =>$invite->eventid,
            'popup_message' => 'Error: That user is already participating in this event.']);
        }

        $event_role = new Event_Role();
        $event_role->userid = $user_id;
        $event_role->eventid = $invite->eventid;
        $event_role->ishost = false;
        $event_role->save();

        $invite->delete();

        return redirect()->route('user.show', [ 'user_id' => $user_id,
        'popup_message' => 'Invitation to join "'.$event->eventname.'" accepted from '.$user->username.'.']);
    }

    public function delete($user_id, $invite_id)
    {
        $invite = Invite::find($invite_id);
        $user = User::find($invite->senderid);
        $invite->delete();

        return redirect()->route('user.show', [ 'user_id' => $user_id,
        'popup_message' => 'Invitation from '.$user->username.' deleted successfully.']);
    }
}
