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
     * FIX: In-memory search for encrypted content
     */
    public function index(Request $request)
    {
        $userId = Auth::id();
        $search = $request->get('search');
        $mood = $request->get('mood');
        $sort = $request->get('sort', 'latest');

        // Build cache key
        $cacheKey = "diaries.user.{$userId}.all"; // We fetch all for search but filter in memory

        // Cache all diaries for the user for faster search/filter
        $diariesCollection = Cache::remember($cacheKey, 600, function () use ($userId) {
            return Diary::with(['analysis' => function ($q) {
                $q->select('id', 'diary_id', 'mood', 'mood_score', 'reflection', 'habit_insight');
            }])
                ->where('user_id', $userId)
                ->get();
        });

        // Apply Filtering in Memory
        $filteredDiaries = $diariesCollection->filter(function ($diary) use ($search, $mood) {
            $matchesMood = true;
            if ($mood) {
                $matchesMood = $diary->analysis && stripos($diary->analysis->mood, $mood) !== false;
            }

            $matchesSearch = true;
            if ($search) {
                $matchesSearch = stripos($diary->content, $search) !== false ||
                    ($diary->analysis && (
                        stripos($diary->analysis->reflection, $search) !== false ||
                        stripos($diary->analysis->habit_insight, $search) !== false
                    ));
            }

            return $matchesMood && $matchesSearch;
        });

        // Apply Sorting
        switch ($sort) {
            case 'oldest':
                $filteredDiaries = $filteredDiaries->sortBy('created_at');
                break;
            case 'mood_score_high':
                $filteredDiaries = $filteredDiaries->sortByDesc(fn($d) => $d->analysis->mood_score ?? -1);
                break;
            case 'mood_score_low':
                $filteredDiaries = $filteredDiaries->sortBy(fn($d) => $d->analysis->mood_score ?? 101);
                break;
            default:
                $filteredDiaries = $filteredDiaries->sortByDesc('created_at');
                break;
        }

        // Manual Pagination
        $perPage = 10;
        $page = $request->get('page', 1);
        $diaries = new \Illuminate\Pagination\LengthAwarePaginator(
            $filteredDiaries->forPage($page, $perPage)->values(),
            $filteredDiaries->count(),
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        return view('user.diary.index', compact('diaries'));
    }

    public function create()
    {
        return view('user.diary.create');
    }

    /**
     * Simpan diary baru
     * FIX: PRG pattern (redirect), consistent AI labels
     */
    public function store(Request $request)
    {
        $request->validate([
            'content' => 'required|string|min:10|max:5000',
        ]);

        DB::beginTransaction();
        try {
            $diary = Diary::create([
                'user_id' => Auth::id(),
                'content' => $request->input('content'),
            ]);

            try {
                $analysisData = $this->callGeminiApi($diary->content);

                $analysis = DiaryAnalysis::create([
                    'diary_id' => $diary->id,
                    'mood' => $analysisData['mood'] ?? 'Netral',
                    'mood_score' => $analysisData['mood_score'] ?? 50,
                    'reflection' => $analysisData['reflection'] ?? 'Tidak ada refleksi.',
                    'habit_insight' => $analysisData['habit_insight'] ?? 'Belum ada habit.',
                ]);

                DB::commit();
                $this->clearUserDiaryCache();

                return redirect()->route('user.diary.index')->with('success', 'Diary & Analisis AI berhasil disimpan!');
            } catch (\Exception $e) {
                DB::commit(); // Tetap commit diary
                $this->clearUserDiaryCache();

                Log::error("AI Analysis error for diary {$diary->id}: " . $e->getMessage());

                return redirect()->route('user.diary.index')->with('success', 'Diary tersimpan, tapi analisis AI gagal. Silakan coba lagi nanti.');
            }
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Diary creation failed: " . $e->getMessage());

            return back()->withInput()->withErrors(['error' => 'Gagal menyimpan diary.']);
        }
    }

    /**
     * Tampilkan detail diary
     */
    public function show($id)
    {
        $userId = Auth::id();
        $diary = Diary::with('analysis')
            ->where('user_id', $userId)
            ->findOrFail($id);

        return view('user.diary.show', compact('diary'));
    }

    public function edit($id)
    {
        $diary = Diary::where('user_id', Auth::id())->findOrFail($id);
        return view('user.diary.edit', compact('diary'));
    }

    /**
     * Update diary
     * FIX: PRG pattern, only re-analyze if needed
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
                    // Update atau create analisis
                    $analysisData = $this->callGeminiApi($diary->content);
                    
                    DiaryAnalysis::updateOrCreate(
                        ['diary_id' => $diary->id],
                        [
                            'mood' => $analysisData['mood'] ?? 'Netral',
                            'mood_score' => $analysisData['mood_score'] ?? 50,
                            'reflection' => $analysisData['reflection'] ?? 'Tidak ada refleksi.',
                            'habit_insight' => $analysisData['habit_insight'] ?? 'Belum ada habit.',
                        ]
                    );
                    $message .= ' Analisis AI telah diperbarui.';
                } catch (\Exception $e) {
                    Log::error("Update AI Analysis Error for diary {$id}: " . $e->getMessage());
                    $message .= ' Namun gagal memperbarui analisis AI.';
                }
            }

            DB::commit();
            $this->clearUserDiaryCache();

            return redirect()->route('user.diary.show', $diary->id)->with('success', $message);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Diary update failed: " . $e->getMessage());

            return back()->withInput()->withErrors(['error' => 'Gagal mengupdate diary.']);
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $diary = Diary::where('user_id', Auth::id())->findOrFail($id);
            $diary->delete(); // DiaryAnalysis cascade should be handled by DB or models

            DB::commit();
            $this->clearUserDiaryCache();

            return redirect()->route('user.diary.index')->with('success', 'Diary berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Diary deletion failed: " . $e->getMessage());

            return back()->withErrors(['error' => 'Gagal menghapus diary.']);
        }
    }

    /**
     * Call Google Gemini API
     * FIX: Use Gemini REST structure and response formats
     */
    private function callGeminiApi(string $content): array
    {
        $apiKey = config('services.gemini.api_key');
        $baseUrl = config('services.gemini.base_url');

        if (!$apiKey || !$baseUrl) {
            throw new \Exception("Konfigurasi API Gemini belum lengkap.");
        }

        $jsonSchema = [
            'mood' => 'string (Singkat: Senang/Sedih/Cemas/Lelah/Marah/Tenang/Stres/Bersemangat)',
            'mood_score' => 'integer (0-100)',
            'reflection' => 'string (Refleksi hangat maks 2 kalimat)',
            'habit_insight' => 'string (Saran praktis maks 2 kalimat)',
        ];

        $prompt = "Analisis diary berikut dan berikan output dalam format JSON murni (tanpa markdown atau teks lain). \n" .
                  "Diary: \"{$content}\" \n" .
                  "Format JSON yang diharapkan: " . json_encode($jsonSchema);

        try {
            // Gemini API uses key in the query parameter for simple REST calls
            $response = Http::withHeaders([
                'Content-Type'  => 'application/json',
            ])
                ->timeout(45)
                ->post($baseUrl . '?key=' . $apiKey, [
                    'contents' => [
                        [
                            'parts' => [
                                ['text' => $prompt]
                            ]
                        ]
                    ],
                    'generationConfig' => [
                        'responseMimeType' => 'application/json',
                    ]
                ]);

            if ($response->failed()) {
                Log::error("Gemini API Error: " . $response->body());
                throw new \Exception("API Error: " . $response->status());
            }

            $raw = $response->json()['candidates'][0]['content']['parts'][0]['text'] ?? '{}';
            $decoded = json_decode($raw, true);

            if (!$decoded) {
                // Try cleaning if JSON is wrapped in markdown
                $clean = preg_replace('/^```json\s*|\s*```$/i', '', trim($raw));
                $decoded = json_decode($clean, true);
            }

            return $decoded ?: throw new \Exception("Invalid JSON from Gemini.");
        } catch (\Exception $e) {
            Log::error("Gemini call exception: " . $e->getMessage());
            throw $e;
        }
    }

    private function clearUserDiaryCache()
    {
        Cache::forget("diaries.user." . Auth::id() . ".all");
    }
}
