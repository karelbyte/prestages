<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $table = "tickets";

    protected $guarded = ['id'];


    public function details()
    {
        return $this->hasMany('App\Models\TicketsDetails', 'idticket', 'id');
    }
}
