<?php

namespace App\Http\Controllers;

use App\{Admin};

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class AdminUserController extends Controller
{
    protected $redirectTo = 'admin';

      public function remove(Request $request){
        $report = Report::where([
            ['report_id',$request['report<-id']],
            ])->first();
        
        $report->delete();

        return response()->json($report, 200);                     
    }
}