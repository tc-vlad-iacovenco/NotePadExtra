<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Insights') }}
            </h2>
            <div class="flex gap-4">
                <a href="{{ route('notes.index') }}" class="bg-gray-600 hover:bg-gray-700 font-bold py-2 px-4 rounded">
                    Switch to Notes
                </a>
                <a href="{{ route('insights.create') }}" class="bg-gray-600 hover:bg-gray-700 font-bold py-2 px-4 rounded">
                    New Insight
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="mb-4">
                        <label class="text-gray-600">Filter by type:</label>
                        <div class="mt-2 space-x-2">
                            @php $types = ['all', 'business', 'technical', 'personal'] @endphp
                            @foreach($types as $type)
                                <a href="{{ route('insights.index', ['type' => $type === 'all' ? null : $type]) }}"
                                   class="inline-block px-3 py-1 rounded {{ request('type') === $type || (request('type') === null && $type === 'all') ? 'bg-blue-500 text-white' : 'bg-gray-200 hover:bg-gray-300' }}">
                                    {{ ucfirst($type) }}
                                </a>
                            @endforeach
                        </div>
                    </div>

                    @forelse ($insights as $insight)
                        <div class="mb-4 p-4 border rounded hover:bg-gray-50">
                            <div class="flex justify-between items-start">
                                <div>
                                    <span class="inline-block px-2 py-1 text-xs rounded
                                        {{ $insight->type === 'business' ? 'bg-green-100 text-green-800' :
                                           ($insight->type === 'technical' ? 'bg-blue-100 text-blue-800' :
                                            'bg-purple-100 text-purple-800') }}">
                                        {{ ucfirst($insight->type) }}
                                    </span>
                                    <h3 class="mt-2 text-lg font-bold">
                                        <a href="{{ route('insights.show', $insight) }}" class="text-blue-600 hover:text-blue-800">
                                            {{ $insight->title }}
                                        </a>
                                    </h3>
                                    @if (auth()->user()->is_admin)
                                        <p class="text-sm text-gray-600">By: {{ $insight->user->name }}</p>
                                    @endif
                                </div>
                                <div class="text-sm text-gray-500">
                                    {{ $insight->created_at->format('M d, Y') }}
                                </div>
                            </div>
                            <p class="mt-2 text-gray-600">{{ Str::limit($insight->content, 150) }}</p>
                            <div class="mt-2 flex justify-between items-center">
                                <span class="text-sm text-gray-500">
                                    {{ $insight->comments->count() }} {{ Str::plural('comment', $insight->comments->count()) }}
                                </span>
                                @if(auth()->id() === $insight->user_id || auth()->user()->is_admin)
                                    <div class="space-x-2">
                                        <a href="{{ route('insights.edit', $insight) }}" class="text-yellow-600 hover:text-yellow-800">Edit</a>
                                        <form action="{{ route('insights.destroy', $insight) }}" method="POST" class="inline">
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
                        <p class="text-gray-500 text-center py-4">No insights found.</p>
                    @endforelse

                    {{ $insights->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
