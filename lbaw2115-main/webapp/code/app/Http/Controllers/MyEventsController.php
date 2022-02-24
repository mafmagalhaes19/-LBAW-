<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class MyEventsController extends Controller
{
    public function index(Request $request)
    {
        $this->eventRepository->pushCriteria(new RequestCriteria($request));
        $events = $this->eventRepository->all();

        return view('items.index')
            ->with('events', $events);
    }

}
