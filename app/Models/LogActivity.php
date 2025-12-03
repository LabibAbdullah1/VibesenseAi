<?php

namespace App\Models;

<<<<<<< HEAD
use Illuminate\Database\Eloquent\Factories\HasFactory;
=======
>>>>>>> 681b617 (create schema database)
use Illuminate\Database\Eloquent\Model;

class LogActivity extends Model
{
<<<<<<< HEAD
    use HasFactory;

    protected $table = 'log_activity'; 

    protected $fillable = [
        'user_id',
        'action',
        'details',
        'ip_address',
        'user_agent'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
=======
    //
>>>>>>> 681b617 (create schema database)
}
