<!-- cuit input -->
@include('crud::fields.inc.wrapper_start')
<label>{!! $field['label'] !!}</label>
@include('crud::fields.inc.translatable_icon')

@if(isset($field['prefix']) || isset($field['suffix'])) <div class="input-group"> @endif
    @if(isset($field['prefix'])) <div class="input-group-prepend"><span class="input-group-text">{!! $field['prefix']
            !!}</span></div> @endif
    <input id="cuit" type="cuit" name="{{ $field['name'] }}"
        value="{{ old(square_brackets_to_dots($field['name'])) ?? $field['value'] ?? $field['default'] ?? '' }}"
        @include('crud::fields.inc.attributes')>
    @if(isset($field['suffix'])) <div class="input-group-append"><span class="input-group-text">{!! $field['suffix']
            !!}</span></div> @endif
    @if(isset($field['prefix']) || isset($field['suffix'])) </div> @endif

{{-- HINT --}}
@if (isset($field['hint']))
<p class="help-block">{!! $field['hint'] !!}</p>
@endif
</div>

{{-- FIELD JS - will be loaded in the after_scripts section --}}
@push('crud_fields_scripts')
<script src="https://rawgit.com/RobinHerbots/jquery.inputmask/4.x/dist/jquery.inputmask.bundle.js"></script>

<script>
    $(document).ready(function(){
        $(cuit).inputmask("99-99999999-9");  //static mask
    });
</script>
@endpush