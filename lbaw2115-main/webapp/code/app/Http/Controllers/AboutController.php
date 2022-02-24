<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class AboutController extends Controller
{
    /**
     * Shows the about page view.
     *
     * @return View
     */
    public function show()
    {
      return view('pages.about');
    }
}
