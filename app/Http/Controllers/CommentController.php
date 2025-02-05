<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Note;
use App\Models\Insight;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(Request $request, $type, $id)
    {
        $validated = $request->validate([
            'content' => 'required',
        ]);

        $model = $this->getModel($type, $id);

        if (!$model) {
            abort(404);
        }

        if (!auth()->user()->is_admin && $model->user_id !== auth()->id()) {
            abort(403);
        }

        $comment = new Comment($validated);
        $comment->user_id = auth()->id();
        $model->comments()->save($comment);

        return back()->with('success', 'Comment added successfully.');
    }

    private function getModel($type, $id)
    {
        return match ($type) {
            'notes' => Note::findOrFail($id),
            'insights' => Insight::findOrFail($id),
            default => null,
        };
    }

    // Other CRUD methods...
}
