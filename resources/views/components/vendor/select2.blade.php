@php
    $classes = "select2-ajax form-input rounded-md shadow-sm mb-3 block w-full";
@endphp
<select
    {{ $attributes->merge(['class' => $classes]) }}
    data-id="{{$id}}"
    {{ $attributes->get('name') ? 'name="' . $attributes->get('name') . '"' : '' }}
    {{$attributes->get('multiple')}}
    x-ref="select"
>
    {{ $slot }}
</select>


@section('js-dependencies')
    @parent
    <link rel="stylesheet" href="{{asset('/vendor/select2/select2.min.css')}}">
    <script src="{{asset('/vendor/select2/select2.min.js')}}"></script>
    <script src="{{asset('/vendor/select2/ru.js')}}"></script>

@endsection
@section('css-dependencies')
{{--    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>--}}

@endsection
@section('scripts')
    @parent
    <script>
        $('.select2-ajax[data-id="{{$id}}"]').select2({
            placeholder: '{{$placeholder}}',
            ajax: {
                url: '{{$url}}',
                dataType: 'json'
            },
            minimumInputLength: 0,
            containerCssClass: "error"
        });
    </script>
@endsection
