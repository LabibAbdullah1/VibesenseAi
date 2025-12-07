<?php

namespace App\Http\Controllers;

use App\Models\Diary;
use App\Models\DiaryAnalysis;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class DiaryController extends Controller
{
    /**
     * Tampilkan daftar diary dengan search, filter, dan sort
     * OPTIMIZED: Eager loading, select specific columns, caching
     */
    public function index(Request $request)
    {
        $userId = Auth::id();

        // Build cache key berdasarkan filter
        $cacheKey = "diaries.user.{$userId}."
            . md5($request->get('search', '') . $request->get('mood', '') . $request->get('sort', 'latest') . $request->get('page', 1));

        // Cache hasil query selama 5 menit
        $diaries = Cache::remember($cacheKey, 300, function () use ($request, $userId) {
            $query = Diary::with(['analysis' => function ($q) {
                // Hanya ambil kolom yang diperlukan dari analysis
                $q->select('id', 'diary_id', 'mood', 'mood_score', 'reflection', 'habit_insight');
            }])
                ->select('id', 'user_id', 'content', 'created_at', 'updated_at')
                ->where('user_id', $userId);

            // Search functionality
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('content', 'like', '%' . $search . '%')
                        ->orWhereHas('analysis', function ($subQ) use ($search) {
                            $subQ->where('reflection', 'like', '%' . $search . '%')
                                ->orWhere('habit_insight', 'like', '%' . $search . '%');
                        });
                });
            }

            // Filter mood
            if ($request->filled('mood')) {
                $query->whereHas('analysis', function ($q) use ($request) {
                    $q->where('mood', 'like', '%' . $request->mood . '%');
                });
            }

            // Sorting
            $sort = $request->get('sort', 'latest');
            switch ($sort) {
                case 'oldest':
                    $query->oldest('created_at');
                    break;
                case 'mood_score_high':
                    $query->join('diary_analysis', 'diaries.id', '=', 'diary_analysis.diary_id')
                        ->orderByDesc('diary_analysis.mood_score')
                        ->select('diaries.*');
                    break;
                case 'mood_score_low':
                    $query->join('diary_analysis', 'diaries.id', '=', 'diary_analysis.diary_id')
                        ->orderBy('diary_analysis.mood_score')
                        ->select('diaries.*');
                    break;
                default:
                    $query->latest('created_at');
                    break;
            }

            return $query->paginate(10);
        });

        return view('user.diary.index', compact('diaries'));
    }

    public function create()
    {
        return view('user.diary.create');
    }

    /**
     * Simpan diary baru
     * OPTIMIZED: Database transaction, better error handling
     */
    public function store(Request $request)
    {
        $request->validate([
            'content' => 'required|string|min:10|max:5000',
        ]);

        DB::beginTransaction();
        try {
            // Simpan diary
            $diary = Diary::create([
                'user_id' => Auth::id(),
                'content' => $request->input('content'),
            ]);

            // Analisis AI
            try {
                $analysisData = $this->callKaApi($diary->content);

                $analysis = DiaryAnalysis::create([
                    'diary_id' => $diary->id,
                    'mood' => $analysisData['mood'] ?? 'Netral',
                    'mood_score' => $analysisData['mood_score'] ?? 50,
                    'reflection' => $analysisData['reflection'] ?? 'Tidak ada refleksi.',
                    'habit_insight' => $analysisData['habit_insight'] ?? 'Belum ada habit.',
                ]);

                DB::commit();

                // Clear cache setelah create
                $this->clearUserDiaryCache();

                Log::info('Diary & Analysis created successfully', [
                    'diary_id' => $diary->id,
                    'analysis_id' => $analysis->id
                ]);

                return view('user.diary.create', [
                    'diary' => $diary,
                    'analysis' => $analysis,
                    'success' => true
                ]);
            } catch (\Exception $e) {
                DB::commit(); // Tetap commit diary walaupun analisis gagal

                Log::error("AI Analysis error for diary {$diary->id}", [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);

                return view('user.diary.create', [
                    'diary' => $diary,
                    'error' => 'Diary tersimpan, tapi analisis AI gagal. Silakan coba lagi nanti.'
                ]);
            }
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error("Diary creation failed", [
                'error' => $e->getMessage()
            ]);

            return back()
                ->withInput()
                ->withErrors(['error' => 'Gagal menyimpan diary. Silakan coba lagi.']);
        }
    }

    /**
     * Tampilkan detail diary
     * OPTIMIZED: Caching dengan eager loading
     */
    public function show($id)
    {
        $userId = Auth::id();
        $cacheKey = "diary.{$id}.user.{$userId}";

        $diary = Cache::remember($cacheKey, 3600, function () use ($id, $userId) {
            return Diary::with('analysis')
                ->where('user_id', $userId)
                ->findOrFail($id);
        });

        return view('user.diary.show', compact('diary'));
    }

    public function edit($id)
    {
        $diary = Diary::select('id', 'user_id', 'content')
            ->where('user_id', Auth::id())
            ->findOrFail($id);

        return view('user.diary.edit', compact('diary'));
    }

    /**
     * Update diary
     * OPTIMIZED: Hanya re-analyze jika content berubah
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'content' => 'required|string|min:10|max:5000',
        ]);

        $diary = Diary::where('user_id', Auth::id())->findOrFail($id);
        $contentChanged = $diary->content !== $request->input('content');

        DB::beginTransaction();
        try {
            $diary->update([
                'content' => $request->input('content'),
            ]);

            $message = 'Diary berhasil diperbarui.';

            if ($contentChanged) {
                try {
                    // Hapus analisis lama
                    if ($diary->analysis) {
                        $diary->analysis->delete();
                    }

                    // Buat analisis baru
                    $this->processAnalysis($diary);
                    $message .= ' Analisis AI telah diperbarui.';
                } catch (\Exception $e) {
                    Log::error("Update AI Analysis Error", [
                        'diary_id' => $diary->id,
                        'error' => $e->getMessage()
                    ]);
                    $message .= ' Namun gagal memperbarui analisis AI.';
                }
            }

            DB::commit();

            // Clear cache
            $this->clearUserDiaryCache($id);

            return redirect()
                ->route('user.diary.show', $diary->id)
                ->with('success', $message);
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error("Diary update failed", [
                'diary_id' => $id,
                'error' => $e->getMessage()
            ]);

            return back()
                ->withInput()
                ->withErrors(['error' => 'Gagal mengupdate diary.']);
        }
    }

    /**
     * Hapus diary
     * OPTIMIZED: Cascade delete dengan transaction
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $diary = Diary::where('user_id', Auth::id())->findOrFail($id);

            // Hapus analisis dulu (jika ada)
            if ($diary->analysis) {
                $diary->analysis->delete();
            }

            // Hapus diary
            $diary->delete();

            DB::commit();

            // Clear cache
            $this->clearUserDiaryCache($id);

            return redirect()
                ->route('user.diary.index')
                ->with('success', 'Diary berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error("Diary deletion failed", [
                'diary_id' => $id,
                'error' => $e->getMessage()
            ]);

            return back()->withErrors(['error' => 'Gagal menghapus diary.']);
        }
    }

    /**
     * Process AI analysis untuk diary
     */
    private function processAnalysis(Diary $diary)
    {
        $analysisData = $this->callKaApi($diary->content);

        DiaryAnalysis::create([
            'diary_id' => $diary->id,
            'mood' => $analysisData['mood'] ?? 'Netral',
            'mood_score' => isset($analysisData['mood_score']) ? (int) $analysisData['mood_score'] : 50,
            'reflection' => $analysisData['reflection'] ?? 'Tidak ada refleksi.',
            'habit_insight' => $analysisData['habit_insight'] ?? 'Belum ada habit.',
        ]);
    }

    /**
     * Call Kolosal AI API
     * OPTIMIZED: Better error handling, timeout management
     */
    private function callKaApi(string $content): array
    {
        $apiKey = config('services.kolosal.api_key') ?? env('KOLOSAL_API_KEY');
        $baseUrl = config('services.kolosal.base_url') ?? env('KOLOSAL_BASE_URL');
        $model = config('services.kolosal.model') ?? env('KOLOSAL_MODEL');

        if (!$apiKey || !$baseUrl || !$model) {
            throw new \Exception("Konfigurasi API Kolosal belum lengkap.");
        }

        $jsonSchema = [
            'mood' => 'string (Singkat: Senang/Sedih/Cemas/Lelah/Marah/Tenang/Stres/Bersemangat)',
            'mood_score' => 'integer (0-100, semakin tinggi semakin positif)',
            'reflection' => 'string (Refleksi hangat dan empatik maks 2 kalimat)',
            'habit_insight' => 'string (Saran aksi praktis maks 2 kalimat)',
        ];

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type'  => 'application/json',
                'Accept'        => 'application/json',
            ])
                ->timeout(45)
                ->retry(2, 1000) // Retry 2x dengan delay 1 detik
                ->post($baseUrl, [
                    'model' => $model,
                    'messages' => [
                        [
                            'role' => 'system',
                            'content' => "Kamu adalah AI psikolog yang empatik. Analisis diary pengguna dan berikan output dalam JSON format berikut: " . json_encode($jsonSchema)
                        ],
                        [
                            'role' => 'user',
                            'content' => $content
                        ]
                    ],
                    'temperature' => 0.7,
                    'response_format' => ['type' => 'json_object'],
                ]);

            if ($response->failed()) {
                throw new \Exception("API Error: " . $response->status() . " - " . $response->body());
            }

            $data = $response->json();
            $raw = $data['choices'][0]['message']['content'] ?? '{}';

            // Clean JSON dari markdown code blocks
            $cleanJson = preg_replace('/^```(?:json)?\s*/i', '', trim($raw));
            $cleanJson = preg_replace('/\s*```$/', '', $cleanJson);

            $decoded = json_decode($cleanJson, true);

            if (!$decoded || json_last_error() !== JSON_ERROR_NONE) {
                throw new \Exception("Invalid JSON response: " . json_last_error_msg());
            }

            // Validasi required fields
            if (!isset($decoded['mood']) || !isset($decoded['mood_score'])) {
                throw new \Exception("Missing required fields in API response");
            }

            return $decoded;
        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            throw new \Exception("Koneksi ke API Kolosal gagal. Periksa internet Anda.");
        } catch (\Illuminate\Http\Client\RequestException $e) {
            throw new \Exception("Request ke API Kolosal gagal: " . $e->getMessage());
        }
    }

    /**
     * Clear cache untuk user diary
     */
    private function clearUserDiaryCache($diaryId = null)
    {
        $userId = Auth::id();

        // Clear list cache (semua kombinasi filter)
        Cache::flush(); // Atau gunakan Cache::tags jika pakai Redis

        // Clear specific diary cache
        if ($diaryId) {
            Cache::forget("diary.{$diaryId}.user.{$userId}");
        }
    }
}
