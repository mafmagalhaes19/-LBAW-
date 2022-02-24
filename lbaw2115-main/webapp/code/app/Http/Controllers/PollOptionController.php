<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\User;
use App\Models\PollOption;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PollOptionController extends Controller
{


    public function vote($event_id, $pollOption_id)
    {
        
        $pollOption = PollOption::find($pollOption_id)->get()->first();
        $pollOption->countvote = ($pollOption->countvote) + 1;
        $pollOption->save();

        return redirect()->route('event.show', ['event_id' => $event_id]);
    }
}
