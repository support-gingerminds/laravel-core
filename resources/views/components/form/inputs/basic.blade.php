@props([
    'id',
    'label',
    'type' => 'text',
    'size' => null,
    'required' => true,
    'disabled' => false,
    'value' => null,
    'placeholder' => null,
    'min' => null,
    'max' => null,
    'step' => null,
    'helper' => null,
])

@php
    $sizeClass = match ($size) {
        'tiny' => 'col-md-2 col-sm-12',
        'sm' => 'col-md-4 col-sm-12',
        'lg' => 'col-md-8 col-sm-12',
        'xl' => 'col-md-12',
        default => 'col-md-6 col-sm-12'
    };
@endphp

<div class="{{ $sizeClass }}">
    <label for="{{ $id }}" class="form-label">{{ $label }} @if($required) <span
            class="text-danger">*</span>@endif</label>
    <input
        type="{{ $type }}"
        id="{{ $id }}"
        name="{{ $id }}"
        class="form-control @error($id) is-invalid @enderror"
        @if(isset($value))value="{{ $value }}" @endif
        @if(isset($placeholder))placeholder="{{ $placeholder }}" @endif
        @if(isset($min))min="{{ $min }}" @endif
        @if(isset($max))max="{{ $max }}" @endif
        @if(isset($step))step="{{ $step }}" @endif
        @if($required) required @endif
        @if($disabled) disabled @endif
        {{ $attributes }}
    />
    @if(isset($helper))
        <div class="form-text" id="basic-addon4">{{ $helper }}</div>
    @endif
    @error($id)
    <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>
