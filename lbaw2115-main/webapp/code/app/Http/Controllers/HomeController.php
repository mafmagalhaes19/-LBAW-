<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Shows the home page view.
     *
     * @return View
     */
    public function show()
    {
      return view('pages.home');
    }
}
