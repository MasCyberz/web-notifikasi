<div class="mb-3">
    <label class="form-label {{ $required ? 'required' : '' }}">{{$label}}</label>
    <div>
        <input type="{{$type}}" name="{{$name}}" class="form-control {{ $class ?? '' }}" aria-describedby="emailHelp" placeholder="{{$placeholder}}" value="{{$value}}">
        @if ($hint)
        <small class="form-hint">{{$hint}}</small>
        @endif
    </div>
</div>
