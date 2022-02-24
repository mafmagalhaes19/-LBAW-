<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use App\Models\Event;
use App\Models\User;

class BrowseController extends Controller
{
  /**
   * Shows the home page view.
   *
   * @return View
   */
  public function show(Request $request)
  {

    //search for query
    if ($request->search_query == "Null") {
      $event_query = Event::all();
      $user_query = User::all();
    } else {
      $event_query = Event::where('eventname', 'ilike', '%' . $request->search_query . '%');
      $user_query = User::where('username', 'ilike', '%' . $request->search_query . '%');
    }

    //search for state
    if (!(is_null($request->event_state) || ($request->event_state == "All"))) {
      $event_query->where('eventstate', $request->event_state);
    }

    if (!(is_null($request->event_tag) || ($request->event_tag == "All"))) {
      $event_query->where('tagid', $request->event_tag);
    }

    //fetch data
    $events = $event_query->get();
    $users = $user_query->get();

    //sort
    switch ($request->sort) {
      case "sdate-asc":
        $events = $events->sortBy('startdate');
        break;
      case "sdate-desc":
        $events = $events->sortByDesc('startdate');
        break;
      case "edate-asc":
        $events = $events->sortBy('enddate');
        break;
      case "edate-desc":
        $events = $events->sortByDesc('enddate');
        break;
      case "dur-asc":
        $events = $events->sortBy('duration');
        break;
      case "dur-desc":
        $events = $events->sortByDesc('duration');
        break;
    }

    return view('pages.browse', ['events' => $events, 'users' => $users, 'popup_message' => $request->popup_message,]);
  }
}
