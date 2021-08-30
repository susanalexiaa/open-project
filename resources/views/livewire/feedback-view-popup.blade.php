<x-jet-dialog-modal wire:model="isFeedbackViewOpen">
    <x-slot name="title">
        <div class="flex justify-between">
            <div>Отзыв</div>

            @if( !is_null($feedback) )
                <div class="rating">
                    @include('partials.rating', ['rating' => $feedback->rating])
                </div>
            @endif
        </div>
    </x-slot>

    <x-slot name="content">
        @if( !is_null($feedback) )
            <div class="data">
                <div class="mb-1 text-gray-500">{{ $feedback->created_at->format('d.m.Y H:i') }}</div>
                <div class="flex mt-1">
                    <div class="mr-3">{{ $feedback->fullname }}</div>
                    <div>{{ $feedback->phone }}</div>
                </div>
            </div>

            <div class="feedback">
                <p>{{ $feedback->comment }}</p>
            </div>

            <p>Фотографии:</p>
            <div class="photos">
                @foreach($feedback->getPhotos() as $src)
                    <img src="{{ $src }}" class="w-auto">
                @endforeach
            </div>
        @endif
    </x-slot>

    <x-slot name="footer">
        <x-jet-secondary-button wire:click="$toggle('isFeedbackViewOpen')" wire:loading.attr="disabled">
            Закрыть
        </x-jet-secondary-button>
    </x-slot>
</x-jet-dialog-modal>