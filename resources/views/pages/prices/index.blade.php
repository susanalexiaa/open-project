<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('prices.price_types.price_types') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="bg-white bg-opacity-25 p-6">
                    <div class="mt-10 sm:mt-0">
                        <div class="md:grid md:grid-cols-3 md:gap-6">
                            <div class="md:col-span-1">
                                <div class="px-4 sm:px-0">
                                    <h3 class="text-lg font-medium text-gray-900">
                                        {{__('prices.price_types.create')}}
                                    </h3>

                                    <p class="mt-1 text-sm text-gray-600">
                                        {{__('prices.price_types.description')}}
                                    </p>
                                    <p class="mt-4 text-sm">
                                        <a class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:shadow-outline-gray disabled:opacity-25 transition ease-in-out duration-150" href="{{route('prices.create')}}">
                                            {{__('Creat')}}
                                        </a>
                                    </p>
                                </div>
                            </div>

                            <div class="mt-5 md:mt-0 md:col-span-2">
                                @foreach($priceTypes as $priceType)
                                    <div class="space-y-3">
                                        <div class="rounded shadow py-5">
                                            <div class="flex items-center flex-wrap">
                                                <div class="flex flex-auto items-center">
                                                    <div class="ml-4">{{$priceType->name}}</div>
                                                </div>
                                                <div class="flex flex-wrap items-center">
                                                    <div class="flex-initial my-2 px-2 text-right">
                                                        <a class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:shadow-outline-gray disabled:opacity-25 transition ease-in-out duration-150" href="{{route('prices.edit', $priceType)}}">
                                                            {{__('Edit')}}
                                                        </a>
                                                    </div>
                                                    <div class="flex-initial px-2 text-right">
                                                        <form action="{{route('prices.destroy', $priceType)}}" method="POST">
                                                            @method('DELETE')
                                                            @csrf
                                                            <x-jet-button type="submit" class="cursor-pointer bg-red-600 text-white hover:bg-red-500 focus:border-red-700 focus:shadow-outline-red active:bg-red-600">
                                                                {{__('Delete')}}
                                                            </x-jet-button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
