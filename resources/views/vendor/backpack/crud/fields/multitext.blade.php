{{-- text input --}}

@include('crud::fields.inc.wrapper_start')
    @if(isset($field['prefix']) || isset($field['suffix'])) <label>{!! $field['label'] !!}</label> @endif
    @include('crud::fields.inc.translatable_icon')

    @if(isset($field['count']) == false || $field['count'] <= 1)

        @if(isset($field['prefix']) || isset($field['suffix'])) <div class="input-group"> @endif
            @if(isset($field['prefix'])) <div class="input-group-prepend"><span class="input-group-text">{!! $field['prefix'] !!}</span></div> @endif
            <input
                type="text"
                name="{{ $field['name'] }}"
                value="{{ old_empty_or_null($field['name'], '') ??  $field['value'] ?? $field['default'] ?? '' }}"
                @include('crud::fields.inc.attributes')
            >
            @if(isset($field['suffix'])) <div class="input-group-append"><span class="input-group-text">{!! $field['suffix'] !!}</span></div> @endif
        @if(isset($field['prefix']) || isset($field['suffix'])) </div> @endif
    
    @else

        @if(isset($field['values']) && is_array($field['values']) && !empty($field['values']))

            @foreach($field['values'] as $value)

                <div class="input-group multitext_{{$field['name']}}_{{$value['id']}}">
                    <input
                        class="form-control input"
                        type="text"
                        name="current_{{ $field['name'] }}[{{$value['id']}}]"
                        value="{{$value['value']}}"
                        @include('crud::fields.inc.attributes')
                    >
                    <a data-init-function="bpFieldInitMultiTextDelete" class="multitext_delete" href="javascript:;">&#9746;</a>
                </div>
                <label></label>

            @endforeach

        @endif

        @for($i = 0; $field['count'] >= $i; $i++)

            <div class="input-group multitext_{{$field['name']}}_{{$i}}" @if($i == $field['count']) style="display:none" @endif>
                <input
                    class="form-control input"
                    type="text"
                    name="{{ $field['name'] }}[{{$i}}]"
                    value=""
                    @include('crud::fields.inc.attributes')
                >
            </div>
            <label></label>

        @endfor

    @endif

    {{-- HINT --}}
    @if (isset($field['hint']))
        <p class="help-block">{!! $field['hint'] !!}</p>
    @endif
@include('crud::fields.inc.wrapper_end')

@push('crud_fields_scripts')

    <script>

 
        function bpFieldInitMultiTextDelete(element) {

            $(element).on('click', function(){

                var _this = $(this);
                var _parent = _this.closest('.input-group');
                var _input = _parent.find('.input');

                //console.log(_input);

                if(_parent.length && _input.length) {
                    _parent.css('display', 'none');
                    _input.val('del');
                }

            });

        }


    </script>

@endpush

@push('crud_fields_styles')

    <style>

        .multitext_delete{
            margin-top: 3.5px;
            margin-left: 10px;
            font-size: 23px;
        }

    </style>

@endpush