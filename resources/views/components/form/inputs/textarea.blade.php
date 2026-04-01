@props([
    'id',
    'label',
    'size' => null,
    'required' => true,
    'disabled' => false,
    'value' => null,
    'placeholder' => null,
    'rows' => 10,
    'helper' => null,
])

@php
    $sizeClass = match ($size) {
        'sm' => 'col-md-4 col-sm-12',
        'lg' => 'col-md-8 col-sm-12',
        'xl' => 'col-md-12',
        default => 'col-md-6 col-sm-12'
    };
@endphp

<div class="{{ $sizeClass }}">
    <label for="{{ $id }}" class="form-label">{{ $label }} @if($required) <span
            class="text-danger">*</span>@endif</label>
    <textarea
        name="{{ $id }}"
        id="{{ $id }}"
        class="form-control @error($id) is-invalid @enderror"
        rows="{{ $rows }}"
        @if(isset($placeholder))placeholder="{{ $placeholder }}" @endif
        @if($required) required @endif
        @if($disabled) disabled @endif
        {{ $attributes }}
    >{{ $value }}</textarea>
    @if(isset($helper))
        <div class="form-text" id="basic-addon4">{{ $helper }}</div>
    @endif
    @error($id)
    <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>
