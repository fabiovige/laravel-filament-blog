<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sender extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'agency_id'];

    public function agency()
    {
        return $this->belongsTo(Agency::class);
    }

    public function tickets()
    {
        return $this->morphMany(Ticket::class, 'ticketable');
    }

    public function user()
    {
        return $this->morphOne(User::class, 'userable');
    }
}
