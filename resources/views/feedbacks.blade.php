<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Feedbacks') }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">


            @livewire('feedbacks')

            {{-- 
            <div class="p-6 bg-white overflow-hidden shadow-xl sm:rounded-lg">
            @foreach($feedbacks as $feedback)
                <div class="flex space-x-4 mb-2">
                    <div class="flex-1">{{ $feedback->created_at->format('d.m.Y H:i') }}</div>
                    <div class="flex-1">{{ $feedback->fullname }}</div>
                    <div class="flex-1">{{ $feedback->phone }}</div>

                    <div class="flex-1">
                        @include('partials.rating', ['rating' => $feedback->rating])
                    </div>
                    <div class="flex-2">
                        <x-jet-button wire:click="addEntity">
                            Почитать
                        </x-jet-button>
                    </div>
                </div>
            @endforeach
            </div> 
            {{ $feedbacks->links() }}
                
            --}}

        </div>
    </div>
</x-app-layout>
