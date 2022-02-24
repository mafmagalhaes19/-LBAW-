<?php

namespace App\Http\Controllers;

use App\{Admin};

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class AdminReportController extends Controller
{
    protected $redirectTo = 'admin';


    public function index($id)
    {
        $admin = Admin::find($id);
        return view('pages.addReport', ['admin' => $admin]);
    }

    public function create(Admin $admin)
    {
        $report = DB::transaction(function () use ($request) {
            $report = new Report();
            $this->authorize('create', $report);
            $report->name = $request->input('name');
            $report->tect = $request->input('text');
            $report->save();

            return $report;
        });
    }

      public function remove(Request $request){
        $report = Report::where([
            ['report_id',$request['report<-id']],
            ])->first();
        
        $report->delete();

        return response()->json($report, 200);                     
    }
}