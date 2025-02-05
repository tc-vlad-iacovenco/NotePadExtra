<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $note->title }}
            </h2>
            @if(auth()->id() === $note->user_id || auth()->user()->is_admin)
                <div class="space-x-2">
                    <a href="{{ route('notes.edit', $note) }}" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded">
                        Edit
                    </a>
                    <form action="{{ route('notes.destroy', $note) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded"
                                onclick="return confirm('Are you sure?')">
                            Delete
                        </button>
                    </form>
                </div>
            @endif
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="prose max-w-none">
                        {{ $note->content }}
                    </div>

                    <div class="mt-8">
                        <h3 class="text-lg font-semibold mb-4">Comments</h3>

                        <form action="{{ route('comments.store', ['type' => 'notes', 'id' => $note->id]) }}" method="POST" class="mb-4">
                            @csrf
                            <textarea name="content" rows="3" class="w-full rounded-md shadow-sm border-gray-300"
                                      placeholder="Add a comment..."></textarea>
                            <button type="submit" class="mt-2 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Add Comment
                            </button>
                        </form>

                        @foreach ($note->comments as $comment)
                            <div class="bg-gray-50 p-4 rounded-lg mb-4">
                                <div class="flex justify-between">
                                    <p class="text-sm text-gray-600">{{ $comment->user->name }}</p>
                                    <p class="text-sm text-gray-500">{{ $comment->created_at->diffForHumans() }}</p>
                                </div>
                                <p class="mt-2">{{ $comment->content }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
