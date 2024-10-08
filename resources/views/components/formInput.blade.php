<div class="mb-3">
    <label class="form-label {{ $required ? 'required' : '' }}">{{ $label }}</label>
    <div>
        <input type="{{ $type }}" name="{{ $name }}" id="{{ $id }}"
            class="form-control @error($name) is-invalid @enderror {{ $class ?? '' }}" aria-describedby="emailHelp"
            placeholder="{{ $placeholder }}" value="{{ $value }}" {{ $required ? 'required' : '' }}
            @if ($type == 'date') min="{{ \Carbon\Carbon::now()->toDateString() }}" @endif>
        @if ($hint)
            <small class="form-hint">{{ $hint }}</small>
        @endif
        @error($name)
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    @if ($name === 'password' || $name === 'password_confirmation' || $name === 'email')
        <div class="invalid-feedback" id="{{ $name }}Error"></div>
    @endif

</div>
