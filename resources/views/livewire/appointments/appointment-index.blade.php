@section('css-dependencies')
    @parent
    <link rel="stylesheet" href="{{asset('vendor/flatpickr/flatpickr.min.css')}}">
    <script src="https://cdn.jsdelivr.net/npm/@ryangjchandler/spruce@2.x.x/dist/spruce.umd.js"></script>
    <script type="text/javascript" src="https://unpkg.com/webcam-easy/dist/webcam-easy.min.js"></script>
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous"/>
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
@endsection
<div>
    <div x-data="app()"
         x-init="initCalendar()"
         x-cloak>

        <div>
            <div class="container mx-auto px-4 py-2" x-show="windows.calendar">
                <div class="flex items-center justify-between py-2 px-6">
                    <div>
                        <span class="text-lg font-bold text-gray-800">Календарь</span>
                    </div>
{{--                    <div class="border rounded-lg px-1" style="padding-top: 2px;">--}}
{{--                        <button--}}
{{--                            type="button"--}}
{{--                            class="leading-none rounded-lg transition ease-in-out duration-100 inline-flex cursor-pointer hover:bg-gray-200 p-1 items-center focus:outline-none"--}}
{{--                        >--}}
{{--                            <svg class="h-6 w-6 text-gray-500 inline-flex leading-none" fill="none" viewBox="0 0 24 24" stroke="currentColor">--}}
{{--                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />--}}
{{--                            </svg>--}}
{{--                        </button>--}}
{{--                        <button--}}
{{--                            type="button"--}}
{{--                            class="leading-none rounded-lg transition ease-in-out duration-100 inline-flex cursor-pointer hover:bg-gray-200 p-1 items-center focus:outline-none"--}}
{{--                        >--}}
{{--                            <svg class="h-6 w-6 text-gray-500 inline-flex leading-none" fill="none" viewBox="0 0 24 24" stroke="currentColor">--}}
{{--                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />--}}
{{--                            </svg>--}}
{{--                        </button>--}}
{{--                    </div>--}}
                </div>
                <div class="bg-white rounded-lg shadow overflow-hidden mb-5">
                    <div class="flex items-center justify-between py-2 px-6">
                        <div>
                            <span x-text="MONTH_NAMES[activeDay.month]" class="text-lg font-bold text-gray-800"></span>
                            <span x-text="activeDay.year" class="ml-1 text-lg text-gray-600 font-normal"></span>
                        </div>
                        <div class="border rounded-lg px-1" style="padding-top: 2px;">
                            <button
                                type="button"
                                class="leading-none rounded-lg transition ease-in-out duration-100 inline-flex cursor-pointer hover:bg-gray-200 p-1 items-center focus:outline-none"
                                :class="{'cursor-not-allowed opacity-25': activeDay.month == 0 }"
                                :disabled="activeDay.month == 0 ? true : false"
                                @click="activeDay.month--; getNoOfDays();">
                                <svg class="h-6 w-6 text-gray-500 inline-flex leading-none"  fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                                </svg>
                            </button>
                            <div class="border-r inline-flex h-6"></div>
                            <button
                                type="button"
                                class="leading-none rounded-lg transition ease-in-out duration-100 inline-flex items-center cursor-pointer hover:bg-gray-200 p-1 focus:outline-none"
                                :class="{'cursor-not-allowed opacity-25': activeDay.month == 11 }"
                                :disabled="activeDay.month == 11 ? true : false"
                                @click="activeDay.month++; getNoOfDays();">
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
                            <template x-for="blankday in blankDays">
                                <div
                                    style="width: 14.28%; height: 60px"
                                    class="inline-block text-left border-r border-b px-4 pt-2 items-left justify-left"
                                >
                                </div>
                            </template>
                            <template x-for="(date, dateIndex) in days" :key="dateIndex">
                                <div style="width: 14.28%; height: 60px" class="flex px-4 pt-2 border-r border-b relative items-end"
                                >
                                    <div
                                        @click="setActiveDate(date)"
                                        x-text="date"
                                        class="inline-flex w-6 h-6 mb-2 items-center justify-center cursor-pointer text-center leading-none rounded-full transition ease-in-out duration-100"
                                        :class="{
                                        'bg-blue-400 text-white': isToday(date) == true,
                                        'bg-blue-500 text-white': isToday(date) && isActive(date),
                                        'bg-blue-200': isActive(date) == true && isToday(date) == false,
                                        'bg-red-200': hasEvents(date) == true
                                    }"
                                    ></div>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
                <!-- Event list -->
                <span>
                    <div class="text-gray-700 text-sm font-bold ml-2 mb-3">{{$this->searchDates}}</div>

                    <div class="max-full mb-4 p-6 bg-white rounded shadow text-sm">
                        <div class="grid col-gap-3 grid-cols-12 justify-start md:justify-between md:items-center">
                            <span class="col-start-1 col-end-13 sm:col-start-1 sm:col-end-7 sm:mt-0 mt-2 lg:justify-center lg:justify-center sm:items-left">
                                <div class="relative lg:mx-auto text-gray-600">
                                    <x-jet-input
                                        wire:model="search"
                                        class=""
                                        type="text"
                                        class="w-full"
                                        placeholder="Поиск"
                                    ></x-jet-input>
                                </div>
                            </span>
                            <span class="col-start-1 col-end-13 sm:col-start-7 sm:col-end-10 sm:mt-0 mt-2 lg:justify-center lg:justify-center sm:items-left">
                               <div class="relative lg:mx-auto text-gray-600">
                                    <x-vendor.flat id="period" class="w-full" wire:model="searchDates" />
                                </div>
                            </span>
                            <div class="col-start-1 col-end-13 sm:col-start-10 sm:col-end-13 sm:mt-0 mt-2 lg:justify-center lg:justify-center sm:items-left text-right">
                                <x-jet-button
                                    @click="showOnMap"
                                    type="button"
                                    class="w-full justify-center"
                                >
                                    Показать на карте
                                </x-jet-button>
                            </div>
                        </div>
                    </div>

            </span>
                <div>
                    <template x-for="item in filteredEvents">
                        <div>
                            <div class="max-full mb-4 p-6 bg-white rounded shadow text-sm">
                                <div class="flex w-full">
                                <span class="flex-inline flex-wrap text-sm text-gray-700 font-semibold py-1" x-text="moment(item.datetime).utc().format('DD.MM hh:mm');">
                                </span>
                                    <span
                                        class="flex-inline text-sm text-gray-500 px-1 py-1 font-bold ml-2"
                                        :class="{
                                        'text-green-700' : item.status === `Состоялся`,
                                        'text-blue-700' : item.status === `Запланирован`,
                                        'text-yellow-300' : item.status === `Просрочнен`,
                                        'text-red-700' : item.status === `Отменен`}"
                                        x-text="item.status"
                                    >
                                </span>
                                </div>
                                <div class="grid-cols-12 grid col-gap-1">
                                    <div class="col-start-1 col-end-13 sm:col-start-1 sm:col-end-3">
                                        <div class="bg-grey-light h-6 font-semibold">Организация:</div>
                                        <span class="title-font text-gray-700 mb-2" x-text="item.customer ? item.customer.name : ''"></span>
                                    </div>
                                    <div class="col-start-1 col-end-13 sm:col-start-3 sm:col-end-5">
                                        <div class="bg-grey-light h-6 font-semibold">Физическое лицо:</div>
                                        <span class="title-font text-gray-700 mb-2" x-text="item.individual ? item.individual.name : ''"></span>
                                    </div>
                                    <div class="col-start-1 col-end-13 sm:col-start-5 sm:col-end-7">
                                        <div class="bg-grey-light h-6 font-semibold">Цель визита:</div>
                                        <span class="title-font text-gray-700 mb-2" x-text="item.objective"></span>
                                    </div>
                                    <div class="col-start-1 col-end-13 sm:col-start-7 sm:col-end-9">
                                        <div class="bg-grey-light h-6 font-semibold">Резюме:</div>
                                        <span class="title-font text-gray-700 mb-2" x-text="item.title"></span>
                                    </div>
                                    <div class="col-start-1 col-end-13 sm:col-start-9 sm:col-end-11">
                                        <div class="bg-grey-light h-6 font-semibold">Сотрудник:</div>
                                        <span class="title-font text-gray-700 mb-2" x-text="item.user ? item.user.name : ''"></span>
                                    </div>
                                    <div class="col-start-1 col-end-13 sm:col-start-11 sm:col-end-13 flex inline-flex justify-end items-center">
                                        <template x-if="item.latitude > 0">
                                            <svg class="h-5 w-5 mr-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6 3 6h-8.5l-1-1H5a2 2 0 00-2 2zm9-13.5V9" />
                                            </svg>
                                        </template>
                                        <div class="text-right grid grid-cols-2 col-gap-1">
                                            <x-jet-button @click="checkIn(item.uuid)" x-show="item.latitude == 0" class="mb-1 bg-blue-500 hover:bg-blue-400" title="Отметиться" alt="Отметиться">
                                                <i class="fas fa-street-view"></i>
                                            </x-jet-button>
{{--                                            <x-jet-button @click="takePhoto(item)" class="mb-1 bg-green-500 hover:bg-green-400" title="Фото" alt="Фото">--}}
{{--                                                <i class="fas fa-camera"></i>--}}
{{--                                            </x-jet-button>--}}

                                            <x-button-link x-bind:href="getAppointmentLink(item.uuid)" class="mb-1 bg-yellow-500 hover:bg-yellow-400" title="Изменить" alt="Изменить">
                                                <i class="far fa-edit"></i>
                                            </x-button-link>
                                            <x-jet-button @click="removeEvent(item.uuid)" class="mb-1 bg-red-500 hover:bg-red-400" title="Удалить" alt="Удалить">
                                                <i class="far fa-trash-alt"></i>
                                            </x-jet-button>
                                            <template x-if="item.cancel_reason == null">
                                                <x-button-link x-bind:href="getCancelLink(item.uuid)" class="mb-1 bg-orange-500 hover:bg-orange-400" title="Отменить" alt="Отменить">
                                                    <i class="fas fa-ban"></i>
                                                </x-button-link>
                                            </template>

                                        </div>
                                    </div>
                                </div>
                                <template class="flex w-full" x-if="item.status === 'Отменен'">
                                    <div>
                                    <span class="flex-inline flex-wrap text-sm text-red-700 font-semibold px-1 py-1">
                                        Причина отмены визита:
                                    </span>
                                        <span class="flex-inline flex-wrap text-sm text-red-700 font-semibold px-1 py-1" x-text="item.cancel_reason">
                                    </span>
                                    </div>
                                </template>
                            </div>

                        </div>
                    </template>
                </div>
            </div>
{{--            <div class="container mx-auto px-4 py-2" x-show.transition.opacity="windows.webcam">--}}
{{--                <div class="bg-white rounded-lg shadow overflow-hidden mb-5 relative">--}}
{{--                    <video class="w-full h-auto" id="webcam" autoplay playsinline></video>--}}
{{--                    <canvas id="canvas" class="absolute bg-transparent z-30 top-0 left-0 right-0 ml-auto mr-auto" x-show="states.webcam.photo"></canvas>--}}
{{--                    <audio id="snapSound" src="{{ public_path('storage/audio/snap.wav') }}" preload = "auto"></audio>--}}
{{--                    <div class="absolute top-5 left-5 bg-gray-100  w-10 h-10 rounded-full text-center shadow flex items-center justify-center cursor-pointer"--}}
{{--                         @click="stopWebcam()"--}}
{{--                         x-show="states.webcam.camera"--}}
{{--                    >--}}
{{--                        <i class="fas fa-chevron-left"></i>--}}
{{--                    </div>--}}
{{--                    <div class="absolute top-5 left-5 bg-gray-100 z-50 w-10 h-10 rounded-full text-center shadow flex items-center justify-center cursor-pointer"--}}
{{--                         @click="removeSnap()"--}}
{{--                         x-show="states.webcam.photo"--}}
{{--                    >--}}
{{--                        <i class="fas fa-chevron-left"></i>--}}
{{--                    </div>--}}
{{--                    <div class="absolute bottom-5 right-5 bg-gray-100 z-50 w-10 h-10 rounded-full text-center shadow flex items-center justify-center cursor-pointer"--}}
{{--                         @click="uploadPhoto()"--}}
{{--                         x-show="states.webcam.photo"--}}
{{--                    >--}}
{{--                        <i class="fas fa-cloud-upload-alt"></i>--}}
{{--                    </div>--}}
{{--                    <div class="absolute bottom-5 right-5 bg-gray-100 w-10 h-10 rounded-full text-center shadow flex items-center justify-center cursor-pointer"--}}
{{--                         @click="makePhoto()"--}}
{{--                         x-show="states.webcam.camera"--}}
{{--                    >--}}
{{--                        <i class="fas fa-camera"></i>--}}
{{--                    </div>--}}
{{--                    <div class="absolute top-0 left-0 w-full h-full flex items-center z-50 justify-center" x-show="states.webcam.loading">--}}
{{--                        <div class="loader ease-linear rounded-full border-8 border-t-8 border-gray-200 h-15 w-15 animate-spin" style="border-top-color:#3498db;"></div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
            <div class="container mx-auto px-4 py-2" x-show.transition.opacity="windows.map">
                <div class="p-2 max-w-xl mx-auto relative absolute left-0 right-0 overflow-hidden">
                    <div class="shadow w-full rounded-lg bg-white overflow-hidden block p-8">
                        <template x-if="Object.values($wire.eventsState).filter(e => e.latitude > 0).length > 0">
                            <div id="map" style="width: 500px; height: 600px;"></div>
                        </template>
                        <span class="flex sm:flex-box mt-4 justify-center justify-center sm:items-left relative">
                            <div class="flex flex-col lg:ml-3 text-gray-500 items-center">
                                <span x-show="Object.values($wire.eventsState).filter(e => e.latitude > 0).length == 0">Визитов не найдено</span>
                                <div class="relative lg:mx-auto text-gray-600">
                                    <button
                                        @click="open('calendar')"
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
            <div class="container mx-auto px-4 py-2" x-show.transition.opacity="windows.cancel">
                <div class="p-2 max-w-xl mx-auto relative absolute left-0 right-0 overflow-hidden">
                    <div class="shadow w-full rounded-lg bg-white overflow-hidden w-full block p-8">
                        <div class="grid grid-cols-1 space-x-4">
                            <div class="flex">
                                <h2 class="flex-inline font-bold text-l mb-6 text-gray-800 border-b pb-2">Отмена визита&nbsp</h2>
                                <span class="flex-inline font-bold text-l mb-6 text-gray-800 border-b pb-2"></span>
                            </div>
                            <div class="inline-block w-full mb-4">
                                <label class="text-gray-800 block mb-1 font-bold text-sm tracking-wide">Причина отмены</label>
                                <div class="relative">
                                    <x-select
                                        wire:model.defer="cancel_reason"
                                        class="block appearance-none w-full bg-gray-200 border-2 border-gray-200 hover:border-gray-500 px-4 py-2 pr-8 rounded-lg leading-tight focus:outline-none focus:bg-white focus:border-blue-500 text-gray-700">
                                        <option value="" disabled selected>Выберите причину отмены</option>
                                        <option value="Болезнь">Болезнь</option>
                                        <option value="Опоздание">Опоздание</option>
                                        <option value="Другое">Другое</option>
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
                                            @click="open('calendar')"
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
        </div>


    </div>

    <script>
        const MONTH_NAMES = ['Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь'];
        const DAYS = ['Пн', 'Вт', 'Ср', 'Чт', 'Пт', 'Сб', 'Вс'];
        function app() {
            return {
                webcam: null,
                activeDay: {},
                blankDays: [],
                actionMenu: false,
                filteredEvents: @entangle('eventsState'),
                days: [],
                events: [],
                today: {},
                windows: {
                    calendar: false,
                    map: false,
                    webcam: false,
                    cancel: false,
                },
                states: {
                    webcam: {
                        camera: false,
                        photo: false,
                        loading: false,
                    }
                },
                photo: null,
                map: null,
                async initCalendar() {
                    this.today = this.createDateObject(new Date)
                    this.activeDay = this.createDateObject(new Date)
                    await this.getNoOfDays()
                    this.open('calendar');
                    this.$wire.searchDates = moment(this.today.object.toISOString()).format('DD.MM.YYYY')
                },
                async getNoOfDays() {
                    let daysInMonth = new Date(this.activeDay.year, this.activeDay.month + 1, 0).getDate();

                    // find where to start calendar day of week
                    let dayOfWeek = new Date(this.activeDay.year, this.activeDay.month).getDay() - 1;

                    let blankdaysArray = [];
                    for (var i = 1; i <= dayOfWeek; i++) {
                        blankdaysArray.push(i);
                    }

                    let daysArray = [];
                    for (var i = 1; i <= daysInMonth; i++) {
                        daysArray.push(i);
                    }
                    this.blankDays = blankdaysArray;
                    this.days = daysArray;
                    this.events = JSON.parse(await this.$wire.getStartEvents(new Date(this.activeDay.year, this.activeDay.month, 1).toISOString(), new Date(this.activeDay.year, this.activeDay.month + 1, 0).toISOString()))
                },
                isToday(date) {
                    return this.today.object.toDateString() === (new Date(this.activeDay.year, this.activeDay.month, date)).toDateString();
                },

                isActive(day) {
                    return this.activeDay.object.toDateString() === (new Date(this.activeDay.year, this.activeDay.month, day)).toDateString();
                },
                setActiveDate(day) {
                    this.activeDay.object.setDate(day);
                    this.activeDay.object.setMonth(this.activeDay.month);
                    this.activeDay = this.createDateObject(this.activeDay.object);
                    this.$wire.searchDates = moment(this.activeDay.object.toISOString()).format('DD.MM.YYYY')
                    $('[data-entity="createLink"]').attr('href', 'appointments/create?date=' + moment(this.activeDay.object.toISOString()).format('YYYY-MM-DD'))
                },
                createDateObject(date) {
                    return {
                        month: date.getMonth(),
                        day: date.getDay(), // день недели
                        date: date.getDate(), // число
                        year: date.getFullYear(),
                        object: date
                    }
                },
                hasEvents(day) {
                    let events = this.events.filter(date => {
                        return new Date(date).getDate() === day
                    })
                    return events.length > 0;
                },
                getEvents() {
                    JSON.parse(this.$wire.getEvents());
                },
                showOnMap() {
                    this.map = ymaps.ready(initMap);
                    this.open('map');

                    let that = this;

                    function initMap() {
                        $('#map').html('');
                        let filtered = Object.values(that.$wire.eventsState).filter(e => e.latitude > 0);
                        if (filtered.length > 0) {
                            const map = new ymaps.Map('map', {
                                center: [55.76, 37.64],
                                controls: ['geolocationControl'],
                                zoom: 10
                            });
                            filtered.forEach(function (item) {
                                let marker = new ymaps.Placemark([item.latitude + Math.random()/10000, item.longitude], {
                                        hintContent: `Сотрудник: ${item.user.name}</br> Цель: ${item.objective} </br> Резюме: ${item.title} </br> Дата и время: ${moment(item.datetime).format('DD.MM.YYYY, hh:mm')}`,
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

                open(window) {
                    let that = this;
                    Object.keys(this.windows).forEach(function (item) {
                        that.windows[item] = false;
                    })
                    this.windows[window] = true;
                },

                openActionMenu(menu) {
                    this.actionMenu = menu;
                },

                closeActionMenu(menu) {
                    if (this.actionMenu === menu) {
                        this.actionMenu = false
                    }
                },

                checkIn(uuid) {
                    if ('geolocation' in navigator) {
                        navigator.geolocation.getCurrentPosition(this.saveLocation.bind(this, uuid));
                    } else {
                        alert('Невозможно получить ваше местоположение');
                    }
                    this.actionMenu = false;
                },

                saveLocation(uuid, position) {
                    this.$wire.saveLocation(uuid, position.coords.latitude, position.coords.longitude);
                    alert('Местоположение обновлено!')
                },

                takePhoto(item) {
                    this.actionMenu = item.uuid;
                    const webcamElement = document.getElementById('webcam');
                    const canvasElement = document.getElementById('canvas');
                    const snapSoundElement = document.getElementById('snapSound');
                    this.webcam = new Webcam(webcamElement, 'user', canvasElement, snapSoundElement);
                    this.open('webcam');
                    this.webcam.start()
                        .then(result => {
                            this.open('webcam');
                        })
                        .catch(err => {
                            console.log(err);
                        });
                    this.states.webcam.camera = true;
                },

                stopWebcam() {
                    this.webcam.stop();
                    this.states.webcam.camera = false;
                    this.open('calendar')
                },

                makePhoto() {
                    this.photo = this.webcam.snap();
                    this.states.webcam.photo = true;
                    this.states.webcam.camera = false;
                },

                removeSnap() {
                    this.photo = null;
                    this.states.webcam.photo = false;
                    this.states.webcam.camera = true;
                },

                uploadPhoto($dispatch) {
                    this.states.webcam.loading = true;

                    axios.post('/appointments/images', {
                        uuid: this.actionMenu,
                        user_id: this.user_id,
                        photo: this.photo,
                    })
                        .then((result) => {
                            this.states.webcam.camera = false;
                            this.states.webcam.loading = false;
                            this.states.webcam.photo = false;
                            this.photo = null;
                            this.open('calendar')
                            this.actionMenu = false;
                            this.$wire.render();
                        })
                },

                showCancelEventModal(uuid) {
                    this.actionMenu = uuid;
                    this.open('cancel');
                },
                cancelEvent() {
                    this.$wire.cancelEvent(this.actionMenu).then(result => {
                        if (result != null && result.status == false) {
                            alert(result.error);
                        } else {
                            this.open('calendar');
                            this.getEventList();
                        }
                    });
                },
                getAppointmentLink(uuid){
                    return '/appointments/' + uuid;
                },
                getCancelLink(uuid){
                    return '/appointments/' + uuid + '/cancel';
                },
                removeEvent(uuid) {
                    if (confirm('Подтвердите удаления записи')){
                        this.$wire.removeEvent(uuid);
                    }
                },
            }
        }
    </script>
</div>
