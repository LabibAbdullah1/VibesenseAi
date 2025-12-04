<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $request->validate([
            'text' => 'required|string'
        ]);
        $message = Message::create([
            'user_id' => Auth::id(),
            'text' => $request->text
        ]);
        $response = Auth::post(' http://apiiiiiiiiiii ',[
            'text' =>$request->text
        ]);
        $message->moodResult()->create([
            'mood'=> $response['mood'],
            'score' => $response['score']
        ]);
        return response()->json([
            'message' => 'message stored',
            'data' => $message
        ]);

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Message $massage)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Message $massage)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Message $massage)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Message $massage)
    {
        //
    }
}
