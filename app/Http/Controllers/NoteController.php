<?php

namespace App\Http\Controllers;

use App\Models\Note;
use Illuminate\Http\Request;

class NoteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return inertia('Home', [
            'notes' => Note::query()
                ->with('tags')
                ->where('user_id', $request->user()->id)
                ->get()
                ->toResourceCollection(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => ['required', 'string', 'max:25'],
            'content' => ['required', 'string', 'max:200'],
        ]);

        Note::create([
            'user_id' => $request->user()->id,
            'title' => $request->string('title'),
            'content' => $request->string('content'),
        ]);

        return back();
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Note $note)
    {
        $request->validate([
            'title' => ['required', 'string', 'max:25'],
            'content' => ['required', 'string', 'max:200'],
        ]);

        $note->update([
            'title' => $request->string('title'),
            'content' => $request->string('content'),
        ]);

        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Note $note)
    {
        $note->forceDelete();

        return back();
    }
}
