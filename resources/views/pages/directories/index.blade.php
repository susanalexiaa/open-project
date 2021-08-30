<x-app-layout>
    <x-slot name="header">
        <h1 class="font-semibold text-sm text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h1>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="bg-gray-200 bg-opacity-25 grid grid-cols-1 md:grid-cols-2">

                    <div class="p-6 border-l border-gray-200">

                        <div class="flex items-center">
                            <div class="ml-4 text-lg leading-7 font-semibold">Контрагенты</div>
                        </div>

                        <div class="ml-4">
                            <div class="mt-2 text-sm text-gray-500">
                            </div>
                            <a href={{ route('contractors.index') }}>
                                <div class="mt-3 flex items-center text-sm font-semibold text-indigo-700">
                                    <div>Посмотреть</div>

                                    <div class="ml-1 text-indigo-500">
                                        <svg viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4">
                                            <path fill-rule="evenodd"
                                                  d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z"
                                                  clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>

                    <div class="p-6 border-t border-gray-200 md:border-t-0 md:border-l">
                        <div class="flex items-center">

                            {{--                            <x-ri-file-list-3-line class="w-8 h-8 text-indigo-400 "/>--}}
                            <div class="ml-4 text-lg leading-7 font-semibold"><a
                                    href="{{route('nomenclatures.index')}}">{{__('nomenclatures.nomenclature')}}</a>
                            </div>
                        </div>

                        <div class="ml-4">
                            <a href="{{route('nomenclatures.index')}}">
                                <div class="mt-3 flex items-center text-sm font-semibold text-indigo-700">
                                    <div>{{__('nomenclatures.all')}}</div>
                                    <div class="ml-1 text-indigo-500">
                                        <svg viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4">
                                            <path fill-rule="evenodd"
                                                  d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z"
                                                  clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>

                    {{--                    <div class="p-6 border-t border-gray-200">--}}
                    {{--                        <div class="flex items-center">--}}
                    {{--                            <svg width="32" height="30" viewBox="0 0 32 30" fill="none" xmlns="http://www.w3.org/2000/svg" class="text-indigo-400">--}}
                    {{--                                <path d="M16 0C12.1339 0 9 3.30527 9 7.38281C9 10.6793 13.575 16.6875 15.3278 18.8684C15.6828 19.3102 16.3178 19.3102 16.6722 18.8684C18.425 16.6875 23 10.6793 23 7.38281C23 3.30527 19.8661 0 16 0ZM16 9.84375C14.7111 9.84375 13.6667 8.74219 13.6667 7.38281C13.6667 6.02344 14.7111 4.92188 16 4.92188C17.2889 4.92188 18.3333 6.02344 18.3333 7.38281C18.3333 8.74219 17.2889 9.84375 16 9.84375ZM1.11778 12.6533C0.787877 12.7925 0.505073 13.0327 0.305839 13.343C0.106605 13.6533 7.90115e-05 14.0194 0 14.3941L0 29.0613C0 29.7246 0.635 30.1781 1.21889 29.932L8.88889 26.25V12.593C8.39778 11.6566 7.99611 10.7449 7.70833 9.87305L1.11778 12.6533ZM16 21.0744C15.2183 21.0744 14.4789 20.7123 13.9717 20.0807C12.8794 18.7213 11.7178 17.1732 10.6667 15.5854V26.2494L21.3333 29.9994V15.5859C20.2822 17.1732 19.1211 18.7219 18.0283 20.0812C17.5211 20.7123 16.7817 21.0744 16 21.0744ZM30.7811 9.44297L23.1111 13.125V30L30.8822 26.7217C31.2122 26.5826 31.495 26.3424 31.6943 26.0321C31.8935 25.7218 32 25.3556 32 24.9809V10.3137C32 9.65039 31.365 9.19688 30.7811 9.44297Z" fill="#8DA2FB"/>--}}
                    {{--                            </svg>--}}
                    {{--                            <div class="ml-4 text-lg leading-7 font-semibold"><a--}}
                    {{--                                    href="{{url('/localities/list')}}">Адресный классификатор</a></div>--}}
                    {{--                        </div>--}}

                    {{--                        <div class="ml-12 mt-3 pr-3">--}}
                    {{--                            <a href="{{url('/localities/list')}}">--}}
                    {{--                                <div class="mt-3 flex items-center text-sm font-semibold text-indigo-700">--}}
                    {{--                                    <div>Посмотреть</div>--}}
                    {{--                                    <div class="ml-1 text-indigo-500">--}}
                    {{--                                        <svg viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4">--}}
                    {{--                                            <path fill-rule="evenodd"--}}
                    {{--                                                  d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z"--}}
                    {{--                                                  clip-rule="evenodd"></path>--}}
                    {{--                                        </svg>--}}
                    {{--                                    </div>--}}
                    {{--                                </div>--}}
                    {{--                            </a>--}}
                    {{--                        </div>--}}
                    {{--                    </div>--}}

                    <div class="p-6 border-l border-t border-gray-200">

                        <div class="flex items-center">
                            <div class="ml-4 text-lg leading-7 font-semibold">Типы цен</div>
                        </div>

                        <div class="ml-4">
                            <div class="mt-2 text-sm text-gray-500">
                            </div>
                            <a href={{ route('prices.index') }}>
                                <div class="mt-3 flex items-center text-sm font-semibold text-indigo-700">
                                    <div>Посмотреть</div>

                                    <div class="ml-1 text-indigo-500">
                                        <svg viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4">
                                            <path fill-rule="evenodd"
                                                  d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z"
                                                  clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>

                    <div class="p-6 border-l  border-t border-gray-200">

                        <div class="flex items-center">
                            <div class="ml-4 text-lg leading-7 font-semibold">Все звонки</div>
                        </div>

                        <div class="ml-4">
                            <div class="mt-2 text-sm text-gray-500">
                            </div>
                            <a href={{ route('contractors.calls') }}>
                                <div class="mt-3 flex items-center text-sm font-semibold text-indigo-700">
                                    <div>Посмотреть</div>

                                    <div class="ml-1 text-indigo-500">
                                        <svg viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4">
                                            <path fill-rule="evenodd"
                                                  d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z"
                                                  clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>

                    <div class="p-6 border-l  border-t border-gray-200">

                        <div class="flex items-center">
                            <div class="ml-4 text-lg leading-7 font-semibold">Объекты учета</div>
                        </div>

                        <div class="ml-4">
                            <div class="mt-2 text-sm text-gray-500">
                            </div>
                            <a href={{ route('entities') }}>
                                <div class="mt-3 flex items-center text-sm font-semibold text-indigo-700">
                                    <div>Посмотреть</div>

                                    <div class="ml-1 text-indigo-500">
                                        <svg viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4">
                                            <path fill-rule="evenodd"
                                                  d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z"
                                                  clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>

                    <div class="p-6 border-l  border-t border-gray-200">

                        <div class="flex items-center">
                            <div class="ml-4 text-lg leading-7 font-semibold">{{ __('Feedbacks') }}</div>
                        </div>

                        <div class="ml-4">
                            <div class="mt-2 text-sm text-gray-500">
                            </div>
                            <a href={{ route('feedbacks') }}>
                                <div class="mt-3 flex items-center text-sm font-semibold text-indigo-700">
                                    <div>Посмотреть</div>

                                    <div class="ml-1 text-indigo-500">
                                        <svg viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4">
                                            <path fill-rule="evenodd"
                                                  d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z"
                                                  clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                    <div class="p-6 border-l  border-t border-gray-200">

                        <div class="flex items-center">
                            <div class="ml-4 text-lg leading-7 font-semibold">Адресный классификатор</div>
                        </div>

                        <div class="ml-4">
                            <div class="mt-2 text-sm text-gray-500">
                            </div>
                            <a href={{ url('/localities/list') }}>
                                <div class="mt-3 flex items-center text-sm font-semibold text-indigo-700">
                                    <div>Посмотреть</div>

                                    <div class="ml-1 text-indigo-500">
                                        <svg viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4">
                                            <path fill-rule="evenodd"
                                                  d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z"
                                                  clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>

                    {{--                    <div class="p-6 border-t border-gray-200 md:border-l">--}}
                    {{--                    </div>--}}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
