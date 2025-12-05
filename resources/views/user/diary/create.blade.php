@extends('layouts.user')

@section('title', 'Tulis Diary')

@section('content')
<div class="max-w-3xl mx-auto bg-white p-6 shadow rounded-lg">
    <h2 class="text-xl font-semibold mb-3">Tulis Diary Baru</h2>

    <form method="POST" action="{{ route('user.diary.store') }}">
        @csrf

        <textarea
            name="content"
            class="w-full h-48 p-3 border rounded-lg focus:ring focus:ring-blue-300"
            placeholder="Ceritakan harimu di sini..."
            required></textarea>

        <label class="flex items-center gap-2 mt-3">
            <input type="checkbox" name="is_private" value="1">
            <span>Jadikan Private</span>
        </label>

        <button class="mt-4 bg-blue-600 text-white px-5 py-2 rounded-lg hover:bg-blue-700">
            Simpan dan Lihat Analisis AI
        </button>
    </form>
</div>
@endsection
