<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class EditUserController extends Controller
{
    protected $redirectTo = 'user';


    public function index($user_id)
    {
        $user = User::find($user_id);
        return view('pages.useredit', ['user' => $user]);
    }

    public function update(Request $request, $user_id)
    {
        $user = User::find($user_id);

        if ($request->name != null)
            $user->username = $request->name;

        if ($request->email != null)
            $user->email = $request->email;

        if ($request->password != null)
            $user->password = bcrypt($request->password);
        
        if ($request->profilePictureUrl != null)
            $user->profilepictureurl = $request->profilePictureUrl;

        if ($request->description != null)
            $user->description = $request->description;
    
        $user->save();

        return redirect()-> route('user.show', $user_id);
    }

}