@props(['name', 'label', 'type' => 'text', 'autocomplete' => null, 'value' => null])
<div>
    <label for="{{ $name }}" class="mb-2 block text-sm font-semibold">{{ $label }}</label>
    @if($type === 'password')
        <x-password-input :id="$name" :name="$name" :autocomplete="$autocomplete" required :aria-invalid="$errors->has($name) ? 'true' : null" :aria-describedby="$errors->has($name) ? $name.'-error' : null" />
    @else
    <input id="{{ $name }}" name="{{ $name }}" type="{{ $type }}" required
        @if($autocomplete) autocomplete="{{ $autocomplete }}" @endif
        @if($type !== 'password') value="{{ $value ?? old($name) }}" @endif
        class="field" @error($name) aria-invalid="true" aria-describedby="{{ $name }}-error" @enderror>
    @endif
    @error($name)<p id="{{ $name }}-error" class="mt-1 text-sm text-red-700">{{ $message }}</p>@enderror
</div>
