<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\User;
use App\Models\Event_Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentEventController extends Controller
{


    public function index($event_id)
    {

        return view('pages.commentevent', ['event_id' => $event_id]);
    }

    public function edit($event_id, $comment_id)
    {

        $comment = Comment::find($comment_id);
        return view('pages.editcomment', ['event_id' => $event_id, 'comment' => $comment, 'comment_id' => $comment_id]);
    }

    public function comment(Request $request, $event_id)
    {
        $comment = new Comment;
        $role = Event_Role::where('ishost', false)->where('eventid', $event_id)->where('userid', Auth::user()->id)->get()->first();
        $comment->role_id = $role->id;
        $comment->messagec = $request->comment_message;
        if($request->get('file') != null){
            $comment->photo = $request->get('file');
        }
        $comment->save();

        return redirect()->route('event.show', ['event_id' => $event_id]);
    }

    public function update(Request $request, $event_id, $comment_id)
    {
        $comment = Comment::find($request->comment_id);

        if ($request->comment_message != null)
            $comment->messagec = $request->comment_message;

        if($request->get('file') != null){
            $comment->photo = $request->get('file');
        }
        $comment->save(); 

        return redirect()-> route('event.show', $event_id);
    }

    public function delete($event_id, $comment_id)
  {
    $comment = Comment::find($comment_id);

    $comment->delete();

    return redirect()->route('event.show', $event_id);
  }

}
