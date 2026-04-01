<div class="col-md-4">
    <label for="{{ $property }}" class="form-label">{{ $label }}</label>
    <input type="text" name="filters[{{ $property }}]" id="{{ $property }}" class="form-control" value="{{ array_key_exists($property, $filters) ? $filters[$property] : null }}"
           @if(isset($placeholder)) placeholder="{{ $placeholder }}" @endif>
</div>
