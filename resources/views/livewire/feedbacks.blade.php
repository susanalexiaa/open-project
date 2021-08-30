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
                <x-jet-button wire:click="$emit( 'openPopupFeedback', {{ $feedback->id }} )">
                    Прочитать
                </x-jet-button>
            </div>
        </div>
    @endforeach

    @if( count($feedbacks) )
    <div class="mt-2">{{ $feedbacks->links()  }}</div>
    @endif

    @livewire('feedback-view-popup')
</div>
