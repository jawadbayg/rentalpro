@props([
    'id',
    'name',
    'label',
    'autocomplete' => 'current-password',
    'inputClass' => 'form-control',
    'required' => true,
])

<div class="mb-3">
    <label for="{{ $id }}" class="form-label">
        {{ $label }}
        @if ($required)
            <span class="text-danger">*</span>
        @endif
    </label>
    <div class="password-field">
        <input
            id="{{ $id }}"
            type="password"
            name="{{ $name }}"
            class="{{ $inputClass }} @error($name) is-invalid @enderror"
            autocomplete="{{ $autocomplete }}"
            @if ($required) required @endif
            {{ $attributes }}
        >
        <button type="button" class="password-field__toggle" data-target="{{ $id }}" aria-label="Show password">
            <i class="fas fa-eye"></i>
        </button>
    </div>
    @error($name)
        <span class="text-danger" role="alert">
            <strong>{{ $message }}</strong>
        </span>
    @enderror
</div>
