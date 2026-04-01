@props([
    'id',
    'label',
    'checked' => false,
    'helper' => null,
])
<div class="form-check form-switch ml-3">
    <input type="hidden" name="{{ $id }}" value="0">
    <input class="form-check-input" type="checkbox" id="{{ $id }}" name="{{ $id }}" value="1" {{ $checked ? 'checked' : '' }}>
    <label class="form-check-label" for="{{ $id }}">{{ $label }}</label>
    @if($helper)
        <div class="form-text" id="basic-addon4-{{ $id }}">{{ $helper }}</div>
    @endif
</div>
