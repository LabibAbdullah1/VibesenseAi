@extends('layouts.app')

@section('title', 'VibeSense AI - Tempat Aman untuk Curhat dan Menulis Diary')

@section('content')
    @include('components.navbar')
    
    <main>
        @include('components.about')
        @include('components.hero')
        @include('components.features')
        @include('components.cta')
        @include('components.testimony')
    </main>
    
    @include('components.footer')
@endsection