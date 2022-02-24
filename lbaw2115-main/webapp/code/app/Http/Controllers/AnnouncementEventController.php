<?php

namespace App\Http\Controllers;


use App\Models\Announcement;
use App\Models\Event_Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AnnouncementEventController extends Controller
{



    public function index($event_id)
    {

        return view('pages.announcementevent', ['event_id' => $event_id]);
    }

    public function announcement(Request $request, $event_id)
    {
        $announcement = new Announcement;
        $role = Event_Role::where('ishost', true)->where('eventid', $event_id)->where('userid', Auth::user()->id)->get()->first();
        $announcement->role_id = $role->id;
        $announcement->messagea = $request->announcement_message;
        $announcement->save();

        return redirect()->route('event.show', ['event_id' => $event_id,'popup_message' => "Announcement added successfully"]);
    }

    public function edit($event_id, $announcement_id)
    {
        $announcement = Announcement::find($announcement_id);
        return view('pages.editannouncement', ['event_id' => $event_id, 'announcement' => $announcement, 'announcement_id' => $announcement_id]);
    }

    public function update(Request $request, $event_id)
    {
        $announcement = Announcement::find($request->announcement_id);

        if ($request->announcement_message != null)
            $announcement->messagea = $request->announcement_message;

    
        $announcement->save(); 

        return redirect()-> route('event.show', [$event_id,'popup_message' => "Announcement edited successfully"]);
    }

    public function delete($event_id, $announcement_id)
    {
        $announcement = Announcement::find($announcement_id);

        $announcement->delete();

        return redirect()->route('event.show', [$event_id,'popup_message' => "Announcement deleted successfully"]);
    }
}
