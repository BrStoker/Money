<!-- field_type_name -->
@include('crud::fields.inc.wrapper_start')

    <label>{!! $field['label'] !!}</label>

    <table class="table">
        <thead>
            @foreach ($field['value'] as $index => $item)
                @if ($index == 0)
                    <tr>
                        @foreach ($item as $subIndex => $subItem)
                            <th style="text-align:center;font-size:12px;padding:0.2rem;">{{ trans('main.' . $subIndex) }}</th>
                        @endforeach
                    </tr>   
                @endif
            @endforeach
        </thead>
        <tbody>
            @foreach ($field['value'] as $index => $item)
                <tr>
                    @foreach ($item as $subIndex => $subItem)
                        <td style="padding:0.2rem;">
                            <textarea style="min-height:75px;padding:0.2rem;font-size:12px;"name="{{ $field['name']}}[{{$index}}][{{$subIndex}}] " class="form-control form-control-sm"> {{ $subItem }}</textarea>
                        </td>
                    @endforeach
                </tr>
            @endforeach
            @foreach ($field['value'] as $index => $item)
                @if ($index == 0)
                    <tr>
                        @foreach ($item as $subIndex => $subItem)
                            <td style="padding:0.2rem;">
                                <textarea style="min-height:75px;padding:0.2rem;font-size:12px;" name="{{ $field['name']}}[{{sizeof((array)$field['value'])+1}}][{{$subIndex}}]" class="form-control form-control-sm"></textarea>
                            </td>
                        @endforeach
                    </tr> 
                @endif
            @endforeach
        </tbody>
    </table>

    {{-- HINT --}}

    @if (isset($field['hint']))
        <p class="help-block">{!! $field['hint'] !!}</p>
    @endif

@include('crud::fields.inc.wrapper_end')

@if ($crud->fieldTypeNotLoaded($field))

    @php
        $crud->markFieldTypeAsLoaded($field);
    @endphp

    {{-- FIELD EXTRA CSS  --}}
    {{-- push things in the after_styles section --}}
    @push('crud_fields_styles')
        <!-- no styles -->
    @endpush

    {{-- FIELD EXTRA JS --}}
    {{-- push things in the after_scripts section --}}
    @push('crud_fields_scripts')
        <!-- no scripts -->
    @endpush
@endif