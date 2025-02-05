<?php

namespace App\Http\Controllers;

use App\Models\Note;
use Illuminate\Http\Request;

class NoteController extends Controller
{
    public function index()
    {
        $notes = auth()->user()->is_admin
            ? Note::with('user')->latest()->paginate(10)
            : auth()->user()->notes()->latest()->paginate(10);

        return view('notes.index', compact('notes'));
    }

    public function create()
    {
        return view('notes.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
        ]);

        auth()->user()->notes()->create($validated);

        return redirect()->route('notes.index')
            ->with('success', 'Note created successfully.');
    }

    public function show(Note $note)
    {
        if (!auth()->user()->is_admin && $note->user_id !== auth()->id()) {
            abort(403);
        }

        $note->load('comments.user');
        return view('notes.show', compact('note'));
    }

    public function edit(Note $note)
    {
        if (!auth()->user()->is_admin && $note->user_id !== auth()->id()) {
            abort(403);
        }

        return view('notes.edit', compact('note'));
    }

    public function update(Request $request, Note $note)
    {
        if (!auth()->user()->is_admin && $note->user_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
        ]);

        $note->update($validated);

        return redirect()->route('notes.show', $note)
            ->with('success', 'Note updated successfully.');
    }

    public function destroy(Note $note)
    {
        if (!auth()->user()->is_admin && $note->user_id !== auth()->id()) {
            abort(403);
        }

        $note->delete();

        return redirect()->route('notes.index')
            ->with('success', 'Note deleted successfully.');
    }
}
