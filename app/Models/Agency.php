<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agency extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function tickets()
    {
        return $this->morphMany(Ticket::class, 'ticketable');
    }

    public function senders()
    {
        return $this->hasMany(Sender::class);
    }

    public function user()
    {
        return $this->morphOne(User::class, 'userable');
    }
}
