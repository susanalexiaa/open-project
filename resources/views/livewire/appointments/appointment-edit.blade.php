@section('css-dependencies')
    @parent
    <script src="https://kit.fontawesome.com/0b9459cc91.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="{{asset('/vendor/toastr/toastr.min.css')}}">
    <script src="{{asset('/vendor/toastr/formAlert.js')}}"></script>
    <script type="module" src="{{ asset("/js/custom_trix.js") }}"></script>
    @trixassets
    <style>
        trix_flex {
            display: flex;
        }

        trix_element {
            padding: 5px;
            flex: 1 1 auto;
            border: 1px dashed rgba(164, 163, 163, 0.5);
            border-right: none;
        }

        trix_element:last-of-type {
            border-right: 1px dashed rgba(164, 163, 163, 0.5);
        }
        .attachment.attachment--preview img{
            max-height: 300px;
            max-width: 300px;
            object-fit: contain;
        }
        .comments .attachment-gallery {
            display: flex;
            flex-wrap: wrap;
            position: relative;
            margin: 0;
            padding: 0;
        }
        .comments .attachment-gallery .attachment {
            flex: 1 0 33%;
            padding: 0 0.5em;
            max-width: 33%;
        }

    </style>
@endsection
<div>
    <div class="shadow w-full rounded-lg bg-white overflow-hidden w-full block p-8">
        <div class="flex">
            <h2 class="flex-inline font-bold text-l mb-6 text-gray-800 border-b pb-2">Детали визита&nbsp</h2>
            <span class="flex-inline font-bold text-l mb-6 text-gray-800 border-b pb-2"></span>
        </div>

        <div class="grid grid-cols-1 space-x-4">
            <div class="inline-block w-full mb-4">
                <label class="text-gray-800 block mb-1 font-bold text-sm tracking-wide">Цель визита</label>
                <div class="relative">
                    <x-select
                        wire:model.defer="objective"
                        class="block appearance-none w-full bg-gray-200 border-2 border-gray-200 hover:border-gray-500 px-4 py-2 pr-8 rounded-lg leading-tight focus:outline-none focus:bg-white focus:border-blue-500 text-gray-700">
                        <option value="" disabled selected>Выберите цель визита</option>
                        <option value="Обмен">Обмен</option>
                        <option value="Первичный визит">Первичный визит</option>
                        <option value="Вторичный визит">Вторичный визит</option>
                        <option value="Двойной визит">Двойной визит</option>
                        <option value="Другое">Другое</option>
                    </x-select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 mb-4">
            <label class="text-gray-800 block mb-1 font-bold text-sm tracking-wide">Резюме</label>
            <x-jet-input
                wire:model.defer="title"
                class="bg-gray-200 appearance-none border-2 border-gray-200 rounded-lg w-full py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-blue-500"
                type="text"
            >
            </x-jet-input>
        </div>

        <div class="grid grid-cols-2">
            <div class="inline-block w-full pr-2">
                <label class="text-gray-800 block mb-1 font-bold text-sm tracking-wide">Часы</label>
                <div class="relative">
                    <x-select wire:model="hours" class="block appearance-none w-full bg-gray-200 border-2 border-gray-200 hover:border-gray-500 px-4 py-2 pr-8 rounded-lg leading-tight focus:outline-none focus:bg-white focus:border-blue-500 text-gray-700">
                        @for($i = 8; $i < 24; $i++)
                            <option value="{{$i}}">{{$i}}</option>
                        @endfor
                    </x-select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                    </div>
                </div>
            </div>

            <div class="inline-block w-full pl-2">
                <label class="text-gray-800 block mb-1 font-bold text-sm tracking-wide">Минуты</label>
                <div class="relative">
                    <x-select wire:model="minutes" class="block appearance-none w-full bg-gray-200 border-2 border-gray-200 hover:border-gray-500 px-4 py-2 pr-8 rounded-lg leading-tight focus:outline-none focus:bg-white focus:border-blue-500 text-gray-700">
                        @for($i = 0; $i < 60; $i+=5)
                            <option value="{{strlen($i) > 1 ? $i : '0'. $i}}">{{strlen($i) > 1 ? $i : '0'. $i}}</option>
                        @endfor
                    </x-select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 mb-4 mt-3">
            <label class="text-gray-800 block mb-1 font-bold text-sm tracking-wide">Комментарии</label>
            <div wire:ignore>
                @trix(Domain\Appointment\Models\Appointment::class, 'content')
            </div>
        </div>

        @if($this->model->images->isNotEmpty())
            <div>
                <div class="flex rounded-md border border-gray-300 shadow-sm px-4 py-2 mt-2">
                    @foreach($this->model->images as $image)
                        <div class="w-auto px-2">
                            <div class="z-50">
                                <button wire:click="deleteImage({{$image['imgId']}})" class="absolute float-right outline-none focus:outline-none bg-blue-500 hover:bg-blue-400">
                                    <svg class="fill-current text-white " xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18">
                                        <path d="M14.53 4.53l-1.06-1.06L9 7.94 4.53 3.47 3.47 4.53 7.94 9l-4.47 4.47 1.06 1.06L9 10.06l4.47 4.47 1.06-1.06L10.06 9z">
                                        </path>
                                    </svg>
                                </button>
                            </div>
                            <div class="bg-gray-400">
                                <a @click="$dispatch('img-modal', {  imgModalSrc: '{{$image['imgModalSrc']}}', imgModalDesc: '{{$image['imgModalDesc']}}'})" class="cursor-pointer">
                                    <img class="object-fit w-full" src="{{$image['imgModalSrc']}}" alt="Placeholder">
                                </a>
                            </div>
                        </div>

                    @endforeach
                </div>
            </div>
        @endif


        <!--                    Actions menu -->
        <div class="relative inline-block text-right mt-2">
            <div>
                <x-button-link href="{{route('appointments.list')}}">
                    Назад
                </x-button-link>
                <x-jet-button
                    class="bg-blue-500 hover:bg-blue-400 focus:bg-blue-400 active:bg-blue-400"
                    wire:click="update"
                >
                    Сохранить
                </x-jet-button>
            </div>
        </div>
        <!--                    Actions menu -->
    </div>
</div>
<script>
    let content = ``;
    @if($this->model->trixRichText->first())
        content = '{!! $this->model->trixRichText->first()->content !!}'
    @endif
    addEventListener("trix-change", function(event) {
        @this.set('comment.content', base64encode($("#appointment-content-new-model").val()))
        @this.set('comment.attachment', base64encode($("#attachment-appointment-content-new-model").val()))
    })
    addEventListener("change-individual-value", function(event) {
        @this.set('individual_id', event.detail.value)
    })
    addEventListener("change-customer-value", function(event) {
        @this.set('customer_id', event.detail.value)
    })
</script>
