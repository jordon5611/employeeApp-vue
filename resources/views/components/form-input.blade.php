<div class="mb-4">
    <label for="{{ $id }}" class="block text-white font-bold mb-2">{{ $label }}:</label>
    <input 
        type="{{ $type ?? ''}}" 
        id="{{ $id }}" 
        name="{{ $name }}" 
        class="w-half px-4 py-2 text-black rounded-lg" 
        value="{{ old($name, $value ?? '') }}" 
        placeholder="{{ $placeholder ?? '' }}"
        {{ $attributes }}
    >
    @error($name)
        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
    @enderror
</div>
