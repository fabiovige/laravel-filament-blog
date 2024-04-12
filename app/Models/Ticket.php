<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = ['details', 'ticketable_type', 'ticketable_id'];

    public function ticketable()
    {
        return $this->morphTo();
    }
}
