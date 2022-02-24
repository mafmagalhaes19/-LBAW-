<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PollOption extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'poll_option';


    protected $fillable = [
        'message',
        'countvote'
    ];

}
