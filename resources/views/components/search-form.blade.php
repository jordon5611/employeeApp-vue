{{-- resources/views/components/search-form.blade.php --}}
@props([
    'route',
    'placeholder' => 'Search...'
])

<div class="max-w-2xl mx-auto">
    <form action="{{ route($route) }}" method="GET" class="mb-6 flex gap-2">
        <input 
            type="text" 
            name="search" 
            value="{{ request('search') }}"
            class="flex-1 px-4 py-2 text-black rounded-lg"
            placeholder="{{ $placeholder }}"
        >
        <button 
            type="submit" 
            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
        >
            @lang('components.search_button')
        </button>
    </form>
</div>