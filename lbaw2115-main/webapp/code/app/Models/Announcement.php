<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'event_announcement';


    protected $fillable = [
        'message'
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\Event_Role', 'idrole');
    }
}
