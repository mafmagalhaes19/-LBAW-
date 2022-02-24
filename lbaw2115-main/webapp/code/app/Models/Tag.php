<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'event_tag';
    protected $fillable = ['tagname'];



        public function events(){
            return $this->belongsToMany('App\Models\Event');
        }
}
