<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Diary extends Model
{
    use HasFactory;
    

    protected $fillable = [
        'user_id',
        'content',
        'is_private',
    ];

    protected $casts = [
        'is_private' => 'boolean',
    ];

    /**
     * Accessor untuk mendekripsi konten dengan aman.
     * Mencegah aplikasi crash jika APP_KEY tidak sesuai (Invalid MAC).
     */
    public function getContentAttribute($value)
    {
        if (empty($value)) return $value;

        try {
            return \Illuminate\Support\Facades\Crypt::decrypt($value);
        } catch (\Exception $e) {
            // Jika gagal dekripsi (misal karena APP_KEY beda), jangan crash.
            // Kembalikan pesan error yang ramah atau blok data asli.
            return "[Konten tidak dapat dibaca: MAC tidak valid. Mohon samakan APP_KEY atau buat ulang diary ini.]";
        }
    }

    /**
     * Mutator untuk mengenkripsi konten saat disimpan.
     */
    public function setContentAttribute($value)
    {
        $this->attributes['content'] = \Illuminate\Support\Facades\Crypt::encrypt($value);
    }


    protected $attributes = [
        'is_private' => true,
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function analysis(): HasOne
    {
        return $this->hasOne(DiaryAnalysis::class);
    }
}
