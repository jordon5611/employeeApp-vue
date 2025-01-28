@props(['routeName', 'createText' => 'Create', 'updateText' => 'Update'])

<button type="submit" 
    {{ $attributes->merge(['class' => 'bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-700 transition duration-300']) }}>
    {{ Request::route()->getName() === $routeName ? $createText : $updateText }}
</button>
