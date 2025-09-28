<?php

namespace App\Http\Controllers;

use App\Models\Note;
use Illuminate\Http\Request;

class ArchivedNoteController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Note $note)
    {
        $note->delete();

        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Note $note)
    {
        $note->restore();

        return back();
    }
}
