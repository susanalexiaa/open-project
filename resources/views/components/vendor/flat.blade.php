@props(['options' => []])

@php
    $id = rand(0, 100);
    $options = array_merge([
                    'mode' => "range",
                    'locale' => "ru",
                    'dateFormat' => 'd.m.Y',
                    'enableTime' => false
                    ], $options);
@endphp
<div >
    <input
        x-data="{instance: undefined}"
        x-init="instance = flatpickr($refs.input, {{ json_encode((object) $options) }});"
        x-ref="input"
        type="text"
        readonly
        placeholder="Период"
        {{ $attributes->merge(['class' => 'form-input rounded-md shadow-sm']) }}
    />
</div>
<script>
    function app_{{$id}}() {
        return {
            mount($dispatch) {

            },
        }
    }
</script>
