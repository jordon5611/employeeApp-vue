@props([
    'routeCreate', // The name of the create route
    'routeUpdate', // The name of the update route
    'titleCreate', // The title for the create page
    'titleEdit', // The title for the edit page
    'item' => null // The model item (e.g., $country, $state, etc.) for edit
])

@if (Request::route()->getName() === str_replace('.store', '.create', $routeCreate))
    <h1 class="text-3xl font-bold mb-6">{{ $titleCreate }}</h1>
    <form action="{{ route($routeCreate) }}" method="POST">
@elseif (Request::route()->getName() === str_replace('.update', '.edit', $routeUpdate))
    <h1 class="text-3xl font-bold mb-6">{{ $titleEdit }}</h1>
    <form action="{{ route($routeUpdate, $item->id ?? '') }}" method="POST">
        @method('PUT')
@endif

@csrf
