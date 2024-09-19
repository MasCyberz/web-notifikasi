<div class="mb-3">
    <label class="form-label {{ $required ? 'required' : '' }}">{{ $label }}</label>
    <div>
        <input type="{{ $type }}" name="{{ $name }}" class="form-control @error($name) is-invalid @enderror {{ $class ?? '' }}"
            aria-describedby="emailHelp" placeholder="{{ $placeholder }}" value="{{ $value }}"
            {{ $required ? 'required' : '' }}>
        @if ($hint)
            <small class="form-hint">{{ $hint }}</small>
        @endif
        @error($name)
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>
