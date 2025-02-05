<?php

namespace App\Http\Controllers;

use App\Models\Insight;
use Illuminate\Http\Request;

class InsightController extends Controller
{
    public function index(Request $request)
    {
        $query = auth()->user()->is_admin
            ? Insight::with('user')
            : auth()->user()->insights();

        if ($request->has('type') && $request->type !== 'all') {
            $query->where('type', $request->type);
        }

        $insights = $query->latest()->paginate(10)
            ->withQueryString(); // Preserve the filter in pagination links

        return view('insights.index', compact('insights'));
    }

    public function create()
    {
        return view('insights.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
            'type' => 'required|in:business,technical,personal',
        ]);

        auth()->user()->insights()->create($validated);

        return redirect()->route('insights.index')
            ->with('success', 'Insight created successfully.');
    }


    public function show(Insight $insight)
    {
        if (!auth()->user()->is_admin && $insight->user_id !== auth()->id()) {
            abort(403);
        }

        $insight->load('comments.user');
        return view('insights.show', compact('insight'));
    }

    public function edit(Insight $insight)
    {
        if (!auth()->user()->is_admin && $insight->user_id !== auth()->id()) {
            abort(403);
        }

        return view('insights.edit', compact('insight'));
    }

    public function update(Request $request, Insight $insight)
    {
        if (!auth()->user()->is_admin && $insight->user_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
        ]);

        $insight->update($validated);

        return redirect()->route('insights.show', $insight)
            ->with('success', 'Insight updated successfully.');
    }

    public function destroy(Insight $insight)
    {
        if (!auth()->user()->is_admin && $insight->user_id !== auth()->id()) {
            abort(403);
        }

        $insight->delete();

        return redirect()->route('insights.index')
            ->with('success', 'Insight deleted successfully.');
    }
}
