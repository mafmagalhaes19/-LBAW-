<?php

namespace App\Http\Controllers;

use App\Models\{Event};
use App\Models\{User};
use Illuminate\Console\Scheduling\Event as SchedulingEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class EditEventController extends Controller
{


    public function show(Request $request, $event_id)
    {
        $events = Event::find($event_id);
        // $this->authorize('show', $events);
        return view('pages.editevent', ['event' => $events, 'popup_message' => $request->popup_message]);
    }

    public function index($event_id)
    {

        return view('pages.editevent');
    }
    

    public function update(Request $request, $event_id)
    {

        $event = Event::find($request->event_id);


        if ($request->eventname != null)
            $event->eventname = $request->eventname;

        if ($request->event_description != null)
            $event->event_description = $request->event_description;

        if ($request->place != null)
            $event->place = $request->place;


        if ($request->get('startdate') != null)
            $event->startdate = $request->get('startdate');


        if ($request->get('enddate') != null)
            $event->enddate = $request->get('enddate');

        if ($request->get('eventstate') != null)
            $event->eventstate = $request->get('eventstate');

        if ($request->get('isprivate') != null)
            $event->isprivate = $request->get('isprivate');

        if ($request->pictureurl != null)
            $event->pictureurl = $request->pictureurl;

        $today = today()->format('Y-m-d');

        if ($event->startdate < $today)
            return view('pages.editevent', ['event' => $event, 'popup_message' => "Error in dates. Either Start Date entered is earlier than today's date or End Date is earlier than Start
        Date."]);
        try {
            $event->save();
        } catch (\Illuminate\Database\QueryException $e) {

            return view('pages.editevent', ['event' => $event, 'popup_message' => "Error in dates. Either Start Date entered is earlier than today's date or End Date is earlier than Start
            Date."]);
        }
        return redirect()->route('event.show', $event_id);
    }
}
