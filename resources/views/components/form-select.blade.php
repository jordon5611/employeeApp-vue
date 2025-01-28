{{-- resources/views/components/form-select.blade.php --}}
@props([
    'name',
    'label',
    'options' => [],
    'selected' => null,
    'placeholder' => null,
    'required' => false
])

<div class="mb-4">
    <label 
        for="{{ $name }}" 
        class="block text-white font-bold mb-2 rounded-lg"
    >
        {{ $label }}:
    </label>
    
    <select 
        id="{{ $name }}" 
        name="{{ $name }}" 
        class="w-half px-4 py-2 text-black rounded-lg"
        {{ $required ? 'required' : '' }}
        {{ $attributes }}
    >
        @if($placeholder)
            <option value="">{{ $placeholder }}</option>
        @endif

        @if(is_array($options))
            @foreach($options as $value => $text)
                <option 
                    value="{{ $value }}"
                    {{ old($name, $selected) == $value ? 'selected' : '' }}
                >
                    {{ $text }}
                </option>
            @endforeach
        @else
            @foreach($options as $option)
                <option 
                    value="{{ $option->id }}"
                    {{ old($name, $selected) == $option->id ? 'selected' : '' }}
                >
                    {{ $option->name }}
                </option>
            @endforeach
        @endif
    </select>

    @error($name)
        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
    @enderror
</div>
