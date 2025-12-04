<?php

namespace App\Models;

<<<<<<< HEAD
<<<<<<< HEAD
use Illuminate\Database\Eloquent\Factories\HasFactory;
=======
>>>>>>> 681b617 (create schema database)
=======
use Illuminate\Database\Eloquent\Factories\HasFactory;
>>>>>>> 7139098 (Add models and migrations for AiRespons, LogActivity, Mood, UserPreference; remove Tells and update moods structure)
use Illuminate\Database\Eloquent\Model;

class LogActivity extends Model
{
<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> 7139098 (Add models and migrations for AiRespons, LogActivity, Mood, UserPreference; remove Tells and update moods structure)
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
<<<<<<< HEAD
=======
    //
>>>>>>> 681b617 (create schema database)
=======
>>>>>>> 7139098 (Add models and migrations for AiRespons, LogActivity, Mood, UserPreference; remove Tells and update moods structure)
}
