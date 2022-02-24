<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

use App\Models\User;
use App\Models\Event;



/**
 * Class User
 * @package App\Models
 *
 * @property \Illuminate\Database\Eloquent\Collection authorWork
 * @property \Illuminate\Database\Eloquent\Collection book
 * @property \Illuminate\Database\Eloquent\Collection review
 * @property \Illuminate\Database\Eloquent\Collection item
 * @property \Illuminate\Database\Eloquent\Collection Work
 * @property \Illuminate\Database\Eloquent\Collection Loan
 * @property \Illuminate\Database\Eloquent\Collection wishList
 * @property string username
 * @property string email
 * @property string pass
 * @property string profilePictureUrl
 * @property boolean isAdmin
 */
class Invite extends Model
{

    use Notifiable;
    public $timestamps  = false;
    protected $table = 'invite';

    public $fillable = [
        'id',
        'receiverid',
        'senderid',
        'eventid'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'receiverid' => 'integer',
        'senderid' => 'integer',
        'eventid' => 'integer'
    ];

    public function sender()
    {
        $sender = User::where('id', $this->senderid)->get();
        return $sender;
    }

    public function receiver()
    {
        $receiver = User::where('id', $this->receiverid)->get();
        return $receiver;
    }

    public function event()
    {
        $event = Event::where('id', $this->eventid)->get();
        return $event;
    }
}

