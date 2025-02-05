<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Notes') }}
            </h2>
            <div class="flex gap-4">
                <a href="{{ route('insights.index') }}" class="bg-gray-600 hover:bg-gray-700 font-bold py-2 px-4 rounded">
                    Switch to Insights
                </a>
                <a href="{{ route('notes.create') }}" class="bg-gray-600 hover:bg-gray-700 font-bold py-2 px-4 rounded">
                    New Note
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    @forelse ($notes as $note)
                        <div class="mb-4 p-4 border rounded hover:bg-gray-50">
                            <div class="flex justify-between items-start">
                                <div>
                                    <h3 class="text-lg font-bold">
                                        <a href="{{ route('notes.show', $note) }}" class="text-blue-600 hover:text-blue-800">
                                            {{ $note->title }}
                                        </a>
                                    </h3>
                                    @if (auth()->user()->is_admin)
                                        <p class="text-sm text-gray-600">By: {{ $note->user->name }}</p>
                                    @endif
                                </div>
                                <div class="text-sm text-gray-500">
                                    {{ $note->created_at->format('M d, Y') }}
                                </div>
                            </div>
                            <p class="mt-2 text-gray-600">{{ Str::limit($note->content, 150) }}</p>
                            <div class="mt-2 flex justify-between items-center">
                                <span class="text-sm text-gray-500">
                                    {{ $note->comments->count() }} {{ Str::plural('comment', $note->comments->count()) }}
                                </span>
                                @if(auth()->id() === $note->user_id || auth()->user()->is_admin)
                                    <div class="space-x-2">
                                        <a href="{{ route('notes.edit', $note) }}" class="text-yellow-600 hover:text-yellow-800">Edit</a>
                                        <form action="{{ route('notes.destroy', $note) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-800"
                                                    onclick="return confirm('Are you sure?')">
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-500 text-center py-4">No notes found.</p>
                    @endforelse

                    {{ $notes->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
