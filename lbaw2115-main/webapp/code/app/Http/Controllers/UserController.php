<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use App\Models\User;
use App\Models\Event;
use App\Models\Report;
use App\Models\Invite;



class UserController extends Controller
{
  /**
   * Shows the user page.
   *
   * @param  int  $id
   * @return View
   */
  public function show($user_id, Request $request)
  {
    $user = User::find($user_id);
    if (is_null($user)) {
      return abort(404);
    } else if ($user->isadmin && !Auth::user()->isadmin){
      return abort(403, "Access Denied");
    }

    $invites_data = Invite::where('receiverid', $user_id) -> get();

    $user_invites = array();

    foreach($invites_data as $invites_datum){
      $user_invite = array();
      $inv_event = Event::find($invites_datum->eventid);
      $inv_sender = User::find($invites_datum->senderid);
  
      array_push($user_invite, $inv_event);
      array_push($user_invite, $inv_sender);
      array_push($user_invite, $invites_datum->id);
      array_push($user_invites, $user_invite);
    }

    $events_as_participant = $user->events_as_participant($user_id);
    $events_as_host = $user->events_as_host($user_id);

    $reports = Report::all();

    $user_stats = [
      'Upvotes' => 0,
      'Comments' => 0,
      'Participations' => count($events_as_participant),
      'Events Hosted' => count($events_as_host),
      'Member Since' => $user->registrationdate
    ];

    if (Auth::check())
      return view('pages.user', ['popup_message' => $request->popup_message, 'user' => $user, 'user_invites' => $user_invites,'popup_message' => $request->popup_message, 'events_as_host' => $events_as_host,'events_as_participant' => $events_as_participant, 'user_stats' => $user_stats, 'reports' => $reports]);
    else
      return redirect("/login");
  }

  public function delete($user_id)
  {
    $user = User::find($user_id);

    $user->delete();

    return redirect()->route('home');
  }

  public static function report_author($report_id)
  {
      $report = Report::find($report_id);
      $user = User::find($report->userid);
      return $user;
  }

  public static function report_event($report_id)
  {
      $report = Report::find($report_id);
      $event = Event::find($report->eventid);
      return $event;
  }
}
