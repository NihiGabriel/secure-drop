<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Secret extends Model
{
    // You MUST have this for the create() method to work
    protected $fillable = ['uuid', 'expires_at']; 
}
