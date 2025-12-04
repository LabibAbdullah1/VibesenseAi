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

class AiRespons extends Model
{
<<<<<<< HEAD
<<<<<<< HEAD
    use HasFactory;

    protected $table = 'AiRespons';
=======
    use HasFactory;

<<<<<<< HEAD
    protected $table = 'ai_respons';
>>>>>>> 7139098 (Add models and migrations for AiRespons, LogActivity, Mood, UserPreference; remove Tells and update moods structure)
=======
    protected $table = 'AiRespons';
>>>>>>> ad56d14 (fix AiRespons)

    protected $fillable = [
        'user_id',
        'mood_id',
        'user_message',
        'ka_reply',
        'motivation',
        'processing_time_ms'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function mood()
    {
        return $this->belongsTo(Mood::class);
    }
<<<<<<< HEAD
=======
    //
>>>>>>> 681b617 (create schema database)
=======
>>>>>>> 7139098 (Add models and migrations for AiRespons, LogActivity, Mood, UserPreference; remove Tells and update moods structure)
}
