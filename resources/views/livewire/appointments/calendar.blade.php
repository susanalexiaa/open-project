@section('css-dependencies')
    @parent
    <link rel="stylesheet" href="{{asset('vendor/flatpickr/flatpickr.min.css')}}">
@endsection
<div>
    <link rel="dns-prefetch" href="//unpkg.com" />
    <link rel="dns-prefetch" href="//cdn.jsdelivr.net" />
    <script src="{{asset('/vendor/moment/moment.min.js')}}"></script>
    <script src="https://cdn.jsdelivr.net/npm/@ryangjchandler/spruce@2.x.x/dist/spruce.umd.js"></script>
    <script type="text/javascript" src="https://unpkg.com/webcam-easy/dist/webcam-easy.min.js"></script>
    <script src="https://api-maps.yandex.ru/2.1/?lang=ru_RU&amp;apikey=f3ec9ee4-28db-410a-a63c-f9feb92bfc00" type="text/javascript"></script>
    <script src="{{asset('vendor/flatpickr/flatpickr.min.js')}}"></script>
    <script src="{{asset('vendor/flatpickr/ru.js')}}"></script>
    <script>
        flatpickr('[data-toggle="datepicker"]', {
            mode: "multiple",
            dateFormat: "d.m.Y",
            locale: "ru"
        });
    </script>

    <style>
        [x-cloak] {
            display: none
        }
    </style>

    <div class="antialiased sans-serif bg-gray-100 h-screen">
        <div
            x-data="app()"
            x-init="[
                initDate(),
                getNoOfDays()
            ]"
            x-cloak
            @image-removed.window = "removeImage($event.detail.id)"
        >
            <div class="container mx-auto px-4 py-2" x-show="isPhotoMode">
                <div class="bg-white rounded-lg shadow overflow-hidden mb-5 relative" >
                    <video class="w-full h-auto" id="webcam" autoplay playsinline></video>
                    <canvas id="canvas" class="absolute bg-transparent z-40 top-0 left-0 right-0 ml-auto mr-auto" x-show="isSnapshotVisible"></canvas>
                    <audio id="snapSound" src="{{ public_path('storage/audio/snap.wav') }}" preload = "auto"></audio>
                </div>
                <div class="absolute sm:bottom-2 bottom-28 flex z-50 justify-center left-1/2 -mr-1/2 transform -translate-x-1/2 -translate-y-1/2">
                    <span class="inline-flex sm:mt-0 mt-2">
                        <span class="flex-wrap text-center" x-show="isPhotoMode && !isSnapshotVisible && !isLoadingState">
                            <a class="ml-3 text-gray-500" href="#" @click="makeSnapshot()">
                                <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="0" class="inline-flex sm:w-20 sm:h-20 w-10 h-10" viewBox="0 0 24 24">
                                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </a>
                            <p class="text-sm font-bold text-gray-500 bg-gray-700 rounded-full px-1 py-1 text-center">фото</p>
                        </span>

                        <span class="flex-wrap text-center ml-2" x-show="isSnapshotVisible && !isLoadingState">
                            <a class="ml-3 text-gray-500" href="#" @click="removeSnapshot()">
                                <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="0" class="sm:w-20 sm:h-20 w-10 h-10" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </a>
                            <p class="text-sm font-bold text-gray-500 bg-gray-700 rounded-full px-1 py-1 text-center">удалить</p>
                        </span>

                        <span class="flex-wrap text-center ml-2" x-show="isSnapshotVisible && !isLoadingState">
                            <a class="ml-3 text-gray-500" href="#" @click="uploadImage($dispatch)">
                                <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="0" class="sm:w-20 sm:h-20 w-10 h-10" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                                </svg>
                            </a>
                            <p class="text-sm font-bold text-gray-500 bg-gray-700 rounded-full px-1 py-1 text-center">прикрепить</p>
                        </span>

                        <span class="flex-wrap text-center ml-2" x-show="isPhotoMode && !isSnapshotVisible && !isLoadingState">
                            <a class="ml-3 text-gray-500" href="#" @click="isPhotoMode = false; openEventModal = true; webcam.stop();">
                                <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="0" class="inline-flex sm:w-20 sm:h-20 w-10 h-10" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                </svg>
                            </a>
                            <p class="text-sm font-bold text-gray-500 bg-gray-700 rounded-full px-1 py-1 text-center">выход</p>
                        </span>

                        <span class="flex-wrap text-center ml-2" x-show="isLoadingState">
                            <a class="ml-3 text-gray-500" href="#">
                                <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="0" class="animate-spin sm:w-20 sm:h-20 w-10 h-10" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                </svg>
                            </a>
                            <p class="text-sm font-bold text-gray-500 bg-gray-700 rounded-full px-1 py-1 text-center">загрузка...</p>
                        </span>
                    </span>
                </div>
            </div>

            <div class="container mx-auto px-4 py-2" x-show="!isPhotoMode && !$wire.openEventModal && !openCancelModal && !openMapModal">
                <div class="flex items-center justify-between py-2 px-6">
                    <div>
                        <span class="text-lg font-bold text-gray-800">Календарь</span>
                    </div>
                    <div>
                        <button class="inline-flex items-center justify-center w-10 h-10 mr-2 text-indigo-100 transition-colors duration-150 bg-indigo-500 rounded-full focus:outline-none hover:bg-indigo-600" @click="createEvent(active.getDate(), $dispatch)">
                            <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20"><path d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" fill-rule="evenodd"></path></svg>
                        </button>
                    </div>
                    <div class="border rounded-lg px-1" style="padding-top: 2px;">
                        <button
                            x-show="calendarHidden"
                            type="button"
                            class="leading-none rounded-lg transition ease-in-out duration-100 inline-flex cursor-pointer hover:bg-gray-200 p-1 items-center focus:outline-none"
                            @click="toggleCalendar">
                            <svg class="h-6 w-6 text-gray-500 inline-flex leading-none" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <button
                            x-show="!calendarHidden"
                            type="button"
                            class="leading-none rounded-lg transition ease-in-out duration-100 inline-flex cursor-pointer hover:bg-gray-200 p-1 items-center focus:outline-none"
                            @click="toggleCalendar">
                            <svg class="h-6 w-6 text-gray-500 inline-flex leading-none" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                            </svg>
                        </button>
                    </div>
                </div>
                <div class="bg-white rounded-lg shadow overflow-hidden mb-5" x-show="!calendarHidden">
                    <div class="flex items-center justify-between py-2 px-6">
                        <div>
                            <span x-text="MONTH_NAMES[month]" class="text-lg font-bold text-gray-800"></span>
                            <span x-text="year" class="ml-1 text-lg text-gray-600 font-normal"></span>
                        </div>
                        <div class="border rounded-lg px-1" style="padding-top: 2px;">
                            <button
                                type="button"
                                class="leading-none rounded-lg transition ease-in-out duration-100 inline-flex cursor-pointer hover:bg-gray-200 p-1 items-center focus:outline-none"
                                :class="{'cursor-not-allowed opacity-25': month == 0 }"
                                :disabled="month == 0 ? true : false"
                                @click="month--; getNoOfDays();">
                                <svg class="h-6 w-6 text-gray-500 inline-flex leading-none"  fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                                </svg>
                            </button>
                            <div class="border-r inline-flex h-6"></div>
                            <button
                                type="button"
                                class="leading-none rounded-lg transition ease-in-out duration-100 inline-flex items-center cursor-pointer hover:bg-gray-200 p-1 focus:outline-none"
                                :class="{'cursor-not-allowed opacity-25': month == 11 }"
                                :disabled="month == 11 ? true : false"
                                @click="month++; getNoOfDays();">
                                <svg class="h-6 w-6 text-gray-500 inline-flex leading-none"  fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div class="-mx-1 -mb-1">
                        <div class="flex flex-wrap" style="margin-bottom: -40px;">
                            <template x-for="(day, index) in DAYS" :key="index">
                                <div style="width: 14.26%" class="px-2 py-2">
                                    <div
                                        x-text="day"
                                        class="text-gray-600 text-sm uppercase tracking-wide font-bold text-center"></div>
                                </div>
                            </template>
                        </div>

                        <div class="flex flex-wrap border-t border-l">
                            <template x-for="blankday in blankdays">
                                <div
                                    style="width: 14.28%; height: 60px"
                                    class="inline-block text-left border-r border-b px-4 pt-2 items-left justify-left"
                                ></div>
                            </template>
                            <template x-for="(date, dateIndex) in no_of_days" :key="dateIndex">
                                <div style="width: 14.28%; height: 60px" class="flex px-4 pt-2 border-r border-b relative items-end">
                                    <div
                                        @click="setActiveDate(date)"
                                        x-text="date"
                                        class="inline-flex w-6 h-6 mb-2 items-center justify-center cursor-pointer text-center leading-none rounded-full transition ease-in-out duration-100"
                                        :class="{ 'bg-blue-500 text-white': isToday(date) == true, 'text-gray-700 hover:bg-blue-200': isToday(date) == false, 'bg-blue-200': isActive(date) == true && isToday(date) == false, 'bg-red-200': hasEvents(date) == true }"
                                    ></div>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
                <!-- Event list -->
                <span>
                    <div class="text-gray-700 text-sm font-bold ml-2" x-text="moment(active.toDateString()).format('DD.MM.YYYY')"></div>
                    <div class="text-gray-500 text-sm font-bold ml-2" x-show="!hasEvents(active.getDate())">На этот день визитов не запланировано</div>

                    <div class="max-full mb-4 p-6 bg-white rounded shadow text-sm" x-show="hasEvents(active.getDate())">
                        <div class="flex flex-col md:flex-row justify-start md:justify-between md:items-center">
                            <span class="lg:inline-flex sm:flex-box sm:mt-0 mt-2 lg:justify-center lg:justify-center sm:items-left">
                                <div class="flex lg:ml-3 text-gray-500 items-center">
                                    <div class="relative lg:mx-auto text-gray-600">
                                        <x-jet-input
                                            wire:model.defer="search"
                                            class="border-2 border-gray-300 bg-white h-8 px-2 pr-2 rounded-lg text-sm focus:outline-none"
                                            type="text"
                                            placeholder="Поиск"
                                        ></x-jet-input>
                                    </div>
                                </div>
                            </span>
                            <span class="lg:inline-flex sm:flex-box sm:mt-0 mt-2 lg:justify-center lg:justify-center sm:items-left">
                                <div class="flex lg:ml-3 text-gray-500 items-center">
                                    <div class="relative lg:mx-auto text-gray-600">
                                        <x-vendor.flat id="period" wire:model="period" />
                                    </div>
                                </div>
                            </span>
                            <span class="lg:inline-flex sm:flex-box sm:mt-0 mt-2 lg:justify-center lg:justify-center sm:items-left">
                                <div class="flex lg:ml-3 text-gray-500 items-center">
                                    <div class="relative lg:mx-auto text-gray-600">
                                        <button
                                            @click="await getEventList()"
                                            type="button"
                                            class="inline-flex justify-center w-full rounded-md border border-transparent px-4 py-2 bg-green-600 text-base leading-6 font-medium text-white shadow-sm hover:bg-green-500 focus:outline-none focus:border-green-700 focus:shadow-outline-green transition ease-in-out duration-150 sm:text-sm sm:leading-5 mr-2"
                                        >
                                            Показать
                                        </button>
                                    </div>
                                </div>
                            </span>
                            <span class="lg:inline-flex sm:flex-box sm:mt-0 mt-2 lg:justify-center lg:justify-center sm:items-left">
                                <div class="flex lg:ml-3 text-gray-500 items-center">
                                    <div class="relative lg:mx-auto text-gray-600">
                                        <button
                                            @click="showOnMap"
                                            type="button"
                                            class="inline-flex justify-center w-full rounded-md border border-transparent px-4 py-2 bg-green-600 text-base leading-6 font-medium text-white shadow-sm hover:bg-green-500 focus:outline-none focus:border-green-700 focus:shadow-outline-green transition ease-in-out duration-150 sm:text-sm sm:leading-5 mr-2"
                                        >
                                            Показать на карте
                                        </button>
                                    </div>
                                </div>
                            </span>
                        </div>
                    </div>

                    @foreach(array_values($event_list) as $evt)
                        <div class="max-full mb-4 p-6 bg-white rounded shadow text-sm">
                            <div class="flex w-full">
                                <span class="flex-inline flex-wrap text-sm text-gray-700 font-semibold px-1 py-1">
                                    {{ $evt['time_formatted'] }}
                                </span>
                                <span
                                    class="flex-inline text-sm text-gray-500 px-1 py-1 font-bold ml-2"
                                    :class="{'text-green-700' : '{{ $evt['status'] == 'Состоялся' }}', 'text-blue-700' : '{{ $evt['status'] == 'Запланирован' }}', 'text-yellow-700' : '{{ $evt['status'] == 'Просрочен' }}', 'text-red-700' : '{{ $evt['status'] == 'Отменен' }}' }">
                                    {{ $evt['status'] }}
                                </span>
                            </div>
                            <div class="flex">
                                <div class="w-3/12 px-1">
                                    <div class="bg-grey-light h-6 font-semibold">Организация:</div>
                                    <span class="title-font text-gray-700 mb-2">{{ $evt['customer'] }}</span>
                                </div>
                                <div class="w-2/12 px-1">
                                    <div class="bg-grey-light h-6 font-semibold">Физическое лицо:</div>
                                    <span class="title-font text-gray-700 mb-2">{{ $evt['individual'] }}</span>
                                </div>
                                <div class="w-2/12 px-1">
                                    <div class="bg-grey-light h-6 font-semibold">Цель визита:</div>
                                    <span class="title-font text-gray-700 mb-2">{{ $evt['objective'] }}</span>
                                </div>
                                <div class="w-2/12 px-1 flex-col">
                                    <div class="bg-grey-light h-6 font-semibold">Резюме:</div>
                                    <span class="title-font text-gray-700 mb-2">{{ $evt['title'] }}</span>
                                </div>
                                <div class="w-2/12 px-1 flex-col">
                                    <div class="bg-grey-light h-6 font-semibold">Сотрудник:</div>
                                    <span class="title-font text-gray-700 mb-2">{{ $evt['user'] }}</span>
                                </div>
                                <div class="w-1/12 px-1 flex inline-flex">
                                    @if( $evt['latitude'] > 0 )
                                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" {{--x-show="{{ $evt['latitude'] > 0 }}"--}}>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6 3 6h-8.5l-1-1H5a2 2 0 00-2 2zm9-13.5V9" />
                                        </svg>
                                    @endif
                                    @if ( sizeof($evt['images']) > 0 )
                                        <svg class="h-5 w-5 ml-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" {{--x-show="{{ sizeof($evt['images']) > 0 }}"--}}>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                                        </svg>
                                    @endif
                                    <svg class="w-5 h-5 fill-current cursor-pointer ml-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" wire:click="showEvent('{{ $evt['uuid'] }}')">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" />
                                    </svg>
                                </div>
                            </div>
                            @if( $evt['status'] == 'Отменен' )
                                <div class="flex w-full">
                                    <span class="flex-inline flex-wrap text-sm text-red-700 font-semibold px-1 py-1">
                                        Причина отмены визита:&nbsp
                                    </span>
                                    <span class="flex-inline flex-wrap text-sm text-red-700 font-semibold px-1 py-1">
                                        {{ $evt['cancel_reason'] }}
                                    </span>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </span>
            </div>

            <!-- Map Modal -->
            <div class="container mx-auto px-4 py-2" x-show.transition.opacity="openMapModal">
                <div class="p-2 max-w-xl mx-auto relative absolute left-0 right-0 overflow-hidden">
                    <div class="shadow w-full rounded-lg bg-white overflow-hidden block p-8">
                        <template x-if="Object.values($wire.event_list).filter(e => e.latitude > 0).length > 0">
                            <div id="map" style="width: 500px; height: 600px;"></div>
                        </template>
                        <span class="flex sm:flex-box mt-4 justify-center justify-center sm:items-left relative">
                            <div class="flex flex-col lg:ml-3 text-gray-500 items-center">
                                <span x-show="Object.values($wire.event_list).filter(e => e.latitude > 0).length == 0">Визитов не найдено</span>
                                <div class="relative lg:mx-auto text-gray-600">
                                    <button
                                        @click="openMapModal=false"
                                        type="button"
                                        class="inline-flex items-center justify-center rounded-md border border-transparent px-4 py-2 bg-green-600 text-base leading-6 font-medium text-white shadow-sm hover:bg-green-500 focus:outline-none focus:border-green-700 focus:shadow-outline-green transition ease-in-out duration-150 sm:text-sm sm:leading-5 mt-2"
                                    >
                                        Закрыть
                                    </button>
                                </div>
                            </div>
                        </span>
                    </div>
                </div>
            </div>

            <!-- Cancel Modal -->
            <div class="container mx-auto px-4 py-2" x-show.transition.opacity="openCancelModal">
                <div class="p-2 max-w-xl mx-auto relative absolute left-0 right-0 overflow-hidden">
                    <div class="shadow w-full rounded-lg bg-white overflow-hidden w-full block p-8">
                        <div class="grid grid-cols-1 space-x-4">
                            <div class="flex">
                                <h2 class="flex-inline font-bold text-l mb-6 text-gray-800 border-b pb-2">Отмена визита&nbsp</h2>
                                <span class="flex-inline font-bold text-l mb-6 text-gray-800 border-b pb-2" x-text="$wire.date_formatted"></span>
                            </div>
                            <div class="inline-block w-full mb-4">
                                <label class="text-gray-800 block mb-1 font-bold text-sm tracking-wide">Причина отмены</label>
                                <div class="relative">
                                    <x-select
                                        wire:model.defer="cancel_reason"
                                        class="block appearance-none w-full bg-gray-200 border-2 border-gray-200 hover:border-gray-500 px-4 py-2 pr-8 rounded-lg leading-tight focus:outline-none focus:bg-white focus:border-blue-500 text-gray-700">
                                        <option value="" disabled selected>Выберите причину отмены</option>
                                        <template x-for="reason in $wire.reasons">
                                            <option :value="reason.value" x-text="reason.label"></option>
                                        </template>
                                    </x-select>
                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                                    </div>
                                </div>
                            </div>
                            <span class="lg:inline-flex sm:flex-box sm:mt-0 mt-2 lg:justify-center lg:justify-center sm:items-left">
                                <div class="flex lg:ml-3 text-gray-500 items-center">
                                    <div class="flex flex-inline relative lg:mx-auto text-gray-600">
                                        <button
                                            @click="cancelEvent"
                                            type="button"
                                            class="inline-flex items-right justify-center w-full rounded-md border border-transparent px-4 py-2 bg-green-600 text-base leading-6 font-medium text-white shadow-sm hover:bg-green-500 focus:outline-none focus:border-green-700 focus:shadow-outline-green transition ease-in-out duration-150 sm:text-sm sm:leading-5 mr-2"
                                        >
                                            Сохранить
                                        </button>
                                        <button
                                            @click="openCancelModal=false"
                                            type="button"
                                            class="inline-flex items-right justify-center w-full rounded-md border border-transparent px-4 py-2 bg-gray-600 text-base leading-6 font-medium text-white shadow-sm hover:bg-gray-500 focus:outline-none focus:border-gray-700 focus:shadow-outline-green transition ease-in-out duration-150 sm:text-sm sm:leading-5 mr-2"
                                        >
                                            Назад
                                        </button>
                                    </div>
                                </div>
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Event Modal -->
            <div class="container mx-auto px-4 py-2" x-show.transition.opacity="$wire.openEventModal">
                <div class="p-2 max-w-xl mx-auto relative absolute left-0 right-0 overflow-hidden">

                    <div class="shadow w-full rounded-lg bg-white overflow-hidden w-full block p-8">
                        <div class="flex">
                            <h2 class="flex-inline font-bold text-l mb-6 text-gray-800 border-b pb-2">Детали визита&nbsp</h2>
                            <span class="flex-inline font-bold text-l mb-6 text-gray-800 border-b pb-2" x-text="$wire.date_formatted"></span>
                        </div>

                        <div class="grid grid-cols-1 space-x-4">
                            <div class="inline-block w-full mb-4">
                                <label class="text-gray-800 block mb-1 font-bold text-sm tracking-wide">Цель визита</label>
                                <div class="relative">
                                    <x-select
                                        wire:model.defer="objective"
                                        class="block appearance-none w-full bg-gray-200 border-2 border-gray-200 hover:border-gray-500 px-4 py-2 pr-8 rounded-lg leading-tight focus:outline-none focus:bg-white focus:border-blue-500 text-gray-700">
                                        <option value="" disabled selected>Выберите цель визита</option>
                                        <template x-for="objective in $wire.objectives">
                                            <option :value="objective.value" x-text="objective.label"></option>
                                        </template>
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
                            <div class="inline-block w-full mb-4 pr-2">
                                <label class="text-gray-800 block mb-1 font-bold text-sm tracking-wide">Часы</label>
                                <div class="relative">
                                    <x-select x-model="$wire.hours" class="block appearance-none w-full bg-gray-200 border-2 border-gray-200 hover:border-gray-500 px-4 py-2 pr-8 rounded-lg leading-tight focus:outline-none focus:bg-white focus:border-blue-500 text-gray-700">
                                        <template x-for="(hour, index) in $wire.times.hours">
                                            <option :value="hour.value" x-text="hour.label"></option>
                                        </template>
                                    </x-select>
                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                                    </div>
                                </div>
                            </div>

                            <div class="inline-block w-full mb-4 pl-2">
                                <label class="text-gray-800 block mb-1 font-bold text-sm tracking-wide">Минуты</label>
                                <div class="relative">
                                    <x-select x-model="$wire.minutes" class="block appearance-none w-full bg-gray-200 border-2 border-gray-200 hover:border-gray-500 px-4 py-2 pr-8 rounded-lg leading-tight focus:outline-none focus:bg-white focus:border-blue-500 text-gray-700">
                                        <template x-for="(time, index) in $wire.times.minutes">
                                            <option :value="time.value" x-text="time.label"></option>
                                        </template>
                                    </x-select>
                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 mb-4" x-show="isEditMode">
                            <label class="text-gray-800 block mb-1 font-bold text-sm tracking-wide">Комментарии</label>
                            <textarea
                                wire:model.defer="comments"
                                rows="2"
                                class="bg-gray-200 appearance-none border-2 border-gray-200 rounded-lg w-full py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-blue-500"
                                type="text"
                            >
                            </textarea>
                        </div>

                        <!--                    Image Gallery -->
                        <div x-data="{ imgModal : false, imgModalSrc : '', imgModalDesc : '' }">
                            <template @img-modal.window="imgModal = true; imgModalSrc = $event.detail.imgModalSrc; imgModalDesc = $event.detail.imgModalDesc;" x-if="imgModal">
                                <div x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-90" x-transition:enter-end="opacity-100 transform scale-100" x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100 transform scale-100" x-transition:leave-end="opacity-0 transform scale-90" x-on:click.away="imgModalSrc = ''" class="p-2 fixed w-full h-100 inset-0 z-50 overflow-hidden flex justify-center items-center bg-black bg-opacity-75">
                                    <div @click.away="imgModal = ''" class="flex flex-col max-w-3xl max-h-full overflow-auto">
                                        <div class="z-50">
                                            <button @click="imgModal = ''" class="float-right pt-2 pr-2 outline-none focus:outline-none">
                                                <svg class="fill-current text-white " xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18">
                                                    <path d="M14.53 4.53l-1.06-1.06L9 7.94 4.53 3.47 3.47 4.53 7.94 9l-4.47 4.47 1.06 1.06L9 10.06l4.47 4.47 1.06-1.06L10.06 9z">
                                                    </path>
                                                </svg>
                                            </button>
                                        </div>
                                        <div class="p-2">
                                            <img class="object-contain h-1/2-screen" :src="imgModalSrc" :alt="imgModalSrc">
                                            <p x-text="imgModalDesc" class="text-center text-white"></p>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>

                        <div
                            x-data="data()"
                            x-init="mount($dispatch)"
                            x-model="images"
                            @image-loaded.window = "images = $event.detail.images"
                            @image-uploaded.window = "refresh($event, $dispatch)"
                        >
                            <template x-if="images.length > 0">
                                <div class="flex rounded-md border border-gray-300 shadow-sm px-4 py-2 mt-2">
                                    <template x-for="(image, index) in images" :key="index">
                                        <div class="w-auto px-2">
                                            <div class="z-50">
                                                <button @click="destroy(image.imgId, $dispatch)" class="absolute float-right outline-none focus:outline-none">
                                                    <svg class="fill-current text-white " xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18">
                                                        <path d="M14.53 4.53l-1.06-1.06L9 7.94 4.53 3.47 3.47 4.53 7.94 9l-4.47 4.47 1.06 1.06L9 10.06l4.47 4.47 1.06-1.06L10.06 9z">
                                                        </path>
                                                    </svg>
                                                </button>
                                            </div>
                                            <div class="bg-gray-400">
                                                <a @click="$dispatch('img-modal', {  imgModalSrc: image.imgModalSrc, imgModalDesc: image.imgModalDesc })" class="cursor-pointer">
                                                    <img class="object-fit w-full" :src="image.imgThumbSrc" alt="Placeholder">
                                                </a>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </template>
                        </div>

                        <!--                    Actions menu -->
                        <div class="relative inline-block text-left mt-2" @click.away="isActionsOpen = false">
                            <div
                                x-transition:enter="transition ease-out duration-100"
                                x-transition:enter-start="transform opacity-0 scale-95"
                                x-transition:enter-end="transform opacity-100 scale-100"
                                x-transition:leave="transition ease-in duration-75"
                                x-transition:leave-start="transform opacity-100 scale-100"
                                x-transition:leave-end="transform opacity-0 scale-95"
                                class="origin-top-right absolute -right-2 mt-2 w-40 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 bottom-2"
                                role="menu"
                                aria-orientation="vertical"
                                aria-labelledby="options-menu"
                                x-show="isActionsOpen">
                                <div class="py-1">
                                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900" role="button" @click="checkIn()" x-show="isEditMode">Отметка</a>
                                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900" role="button" @click="takePhoto()" x-show="isEditMode">Фото</a>
                                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900" role="button" @click="removeEvent()" x-show="isEditMode">Удалить</a>
                                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900" role="button" @click="showCancelEventModal()" x-show="isEditMode">Отменить</a>
                                </div>
                                <hr x-show="isEditMode">
                                <div class="py-1">
                                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900" role="button" @click="closeEventModal()">Назад</a>
                                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900" role="button" @click="saveEvent()">Сохранить</a>
                                </div>
                            </div>
                            <div>
                                <button type="button" class="inline-flex justify-center w-full rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-100 focus:ring-indigo-500" id="options-menu" aria-haspopup="true" aria-expanded="true" @click="isActionsOpen = !isActionsOpen">
                                    Действия
                                    <svg class="-mr-1 ml-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                        <!--                    Actions menu -->
                    </div>
                </div>
            </div>
            <!--        Event Modal -->
        </div>

        <script>
            const MONTH_NAMES = ['Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь'];
            const DAYS = ['Пн', 'Вт', 'Ср', 'Чт', 'Пт', 'Сб', 'Вс'];

            function data() {
                return {
                    images: [],
                    url: '',

                    mount($dispatch) {
                        this.images = [];
                    },

                    load() {
                    },

                    refresh($event, $dispatch) {
                        if ($event.detail.id != 0) {
                            axios.get('/appointments/' + $event.detail.id)
                                .then((result) => {
                                    this.images = result.data.appointment.images;
                                });
                        } else {
                            this.images = [];
                        }
                    },

                    destroy(id, $dispatch) {
                        let index = this.images.findIndex(
                            el => (
                                el.imgId === id
                            )
                        );

                        this.images.splice(index, 1);

                        $dispatch('image-removed', {'id': id});
                    }
                }
            }

            function app() {
                return {
                    event_list: @entangle('event_list'),
                    year: '',
                    month: '',
                    active: '',

                    blankdays: [],
                    no_of_days: [],
                    user_id: window.user.id,

                    isEditMode: @entangle('isEditMode'),
                    isPhotoMode: false,
                    isSnapshotVisible: false,
                    isLoadingState: false,
                    isActionsOpen: false,
                    openEventModal: @entangle('openEventModal'),
                    openCancelModal: false,
                    openMapModal: false,
                    calendarHidden: false,

                    webcam: null,
                    photo: null,

                    removeImage(id) {
                        axios.delete('/appointments/images', {
                            headers: {'Content-Type': 'application/json; charset=utf-8'},
                            data: {
                                uuid: this.$wire.uuid,
                                user_id: this.user_id,
                                image_id: id,
                            }});
                    },

                    uploadImage($dispatch) {
                        this.isLoadingState = true;

                        axios.post('/appointments/images', {
                            uuid: this.$wire.uuid,
                            user_id: this.user_id,
                            photo: this.photo,
                        })
                            .then((result) => {
                                $dispatch('image-uploaded', {'id' : result.data.uuid});

                                this.isSnapshotVisible = false;
                                this.isLoadingState = false;
                            });
                    },

                    removeSnapshot() {
                        this.isSnapshotVisible = false;
                    },

                    makeSnapshot() {
                        this.photo = this.webcam.snap();
                        this.isSnapshotVisible = true;
                    },

                    takePhoto() {
                        this.openEventModal = false;
                        this.isActionsOpen = false;

                        if (!this.isPhotoMode) {
                            this.webcam.start()
                                .then(result => {
                                    console.log("webcam started");
                                    this.isPhotoMode = true;
                                })
                                .catch(err => {
                                    console.log(err);
                                });
                        } else {
                            this.webcam.stop();
                            this.isPhotoMode = false;
                            console.log("webcam stopped");
                        }
                    },

                    initDate() {
                        let today = new Date();

                        this.active = today;
                        this.month = today.getMonth();
                        this.year = today.getFullYear();

                        this.getEventList();

                        this.calendarHidden = this.$wire.calendar_hidden;

                        const webcamElement = document.getElementById('webcam');
                        const canvasElement = document.getElementById('canvas');
                        const snapSoundElement = document.getElementById('snapSound');
                        this.webcam = new Webcam(webcamElement, 'user', canvasElement, snapSoundElement);
                    },

                    isToday(date) {
                        return (new Date()).toDateString() === (new Date(this.year, this.month, date)).toDateString();
                    },

                    isActive(day) {
                        return this.active.toDateString() === (new Date(this.year, this.month, day)).toDateString();
                    },

                    setActiveDate(day) {
                        this.active = new Date(this.year, this.month, day);
                        this.getEventList();
                    },

                    hasEvents(day) {
                        const events = Object.values(this.$wire.events).filter(e => new Date(e.date).toDateString() === new Date(this.year, this.month, day).toDateString());

                        const event_list = Object.values(this.$wire.event_list).filter(e => new Date(e.date).toDateString() === new Date(this.year, this.month, day).toDateString());

                        return (events.length > 0 || event_list.length > 0);
                    },

                    async getEventList() {
                        await this.$wire.getEventList(
                            moment(this.active.toDateString()).format('DD.MM.YYYY'),
                            moment(this.active.toDateString()).format('DD.MM.YYYY'),
                        );
                    },

                    createEvent(day, $dispatch) {
                        this.openEventModal = true;
                        this.isEditMode = false;

                        this.$wire.createEvent(this.year, this.month+1, day);

                        $dispatch('image-uploaded', {'id' : 0});
                    },

                    showEvent(uuid, $dispatch) {
                        $dispatch('image-uploaded', {'id' : uuid});

                        this.openEventModal = true;
                        this.isEditMode = true;

                        this.$wire.showEvent(uuid);

                        // if (event.customer_id) {
                        //     $dispatch('set-select2-value', {
                        //         id: event.customer_id,
                        //         text: event.customer.name
                        //     });
                        // } else {
                        //     $dispatch('set-select2-value', {
                        //         id: 0
                        //     });
                        // }
                    },

                    saveEvent() {
                        this.isActionsOpen = false;

                        this.$wire.saveEvent(moment(this.active.toDateString()).format('YYYY-MM-DD')).then(result => {
                            if (result != null && result.status == false) {
                                alert(result.error);
                            } else {
                                this.closeEventModal();
                            }
                        });

                        this.getEventList();
                    },

                    async removeEvent() {
                        this.isActionsOpen = false;

                        let result = await this.$wire.removeEvent();

                        await this.getEventList();

                        this.closeEventModal();
                    },

                    showCancelEventModal() {
                        this.isActionsOpen = false;
                        this.openEventModal = false;

                        this.openCancelModal = true;
                    },

                    cancelEvent() {
                        this.$wire.cancelEvent().then(result => {
                            if (result != null && result.status == false) {
                                alert(result.error);
                            } else {
                                this.openCancelModal = false;
                                this.getEventList();
                            }
                        });
                    },

                    closeEventModal() {
                        this.$wire.resetEvent();

                        this.openEventModal = false;
                        this.isActionsOpen = false;
                    },

                    showOnMap() {
                        ymaps.ready(initMap);

                        this.openMapModal = true;

                        let that = this;

                        function initMap() {
                            let filtered = Object.values(that.$wire.event_list).filter(e => e.latitude > 0);

                            if (filtered.length > 0)
                            {
                                const map = new ymaps.Map('map', {
                                    center: [55.76, 37.64],
                                    controls: ['geolocationControl'],
                                    zoom: 10
                                });

                                filtered.forEach(function (item) {
                                    let marker = new ymaps.Placemark([item.latitude, item.longitude], {
                                            hintContent: `Сотрудник: ${item.user}</br> Цель: ${item.objective} </br> Резюме: ${item.title} </br> Дата и время: ${item.date}, ${item.hours}:${item.minutes}`,
                                            balloonContent: '',
                                            iconContent: ''
                                        },
                                        {
                                            preset: "islands#blueStretchyIcon",
                                            balloonCloseButton: false,
                                            hideIconOnBalloonOpen: false
                                        });

                                    map.geoObjects.add(marker);
                                    showMap = true;
                                });
                            }
                        }
                    },

                    closeMapModal() {
                        this.openMapModal = false;
                    },

                    checkIn() {
                        this.isActionsOpen = false;

                        if ('geolocation' in navigator) {
                            navigator.geolocation.getCurrentPosition(this.saveLocation.bind(this));
                        } else {
                            alert('Невозможно получить ваше местоположение');
                        }
                    },

                    saveLocation(position) {
                        this.$wire.saveLocation(position.coords.latitude, position.coords.longitude);

                        this.getEventList();

                        this.closeEventModal();
                    },

                    toggleCalendar(){
                        this.calendarHidden = !this.calendarHidden;

                        this.$wire.toggleCalendar(this.calendarHidden);
                    },

                    getNoOfDays() {
                        let daysInMonth = new Date(this.year, this.month + 1, 0).getDate();

                        // find where to start calendar day of week
                        let dayOfWeek = new Date(this.year, this.month).getDay() - 1;

                        let blankdaysArray = [];
                        for (var i = 1; i <= dayOfWeek; i++) {
                            blankdaysArray.push(i);
                        }

                        let daysArray = [];
                        for (var i = 1; i <= daysInMonth; i++) {
                            daysArray.push(i);
                        }

                        this.blankdays = blankdaysArray;
                        this.no_of_days = daysArray;
                    },
                }
            }
        </script>
    </div>
</div>
