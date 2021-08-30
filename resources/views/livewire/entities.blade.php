<x-jet-action-section>
    <x-slot name="title">{{__('Accounting objects')}}</x-slot>
    <x-slot name="description">
        @if( !is_null(\Auth::user()->company) )
            <x-jet-button wire:click="$emit('openAddEntityModal')">
                {{__("Create")}}
            </x-jet-button>
        @else
            <p>{{__('To add accounting objects, add your organization in the settings')}}</p>
        @endif
    </x-slot>
    <x-slot name="content">
        @foreach($entities as $entity)
            <div class="flex space-x-4 mb-2">
                <div class="flex-1">{{ $entity->name }}</div>

                <div class="flex-1">
                    @include('partials.rating', ['rating' => $entity->getRating()])
                </div>
                <div class="flex-2">
                    <a target="_blank" href="{{ route('showQRCode', $entity->id) }}">
                        <x-jet-button>
                            {{__('entities.generate_qr')}}
                        </x-jet-button>
                    </a>

                    <x-jet-danger-button wire:click="$emit( 'openDeleteEntityModal', {{ $entity->id }} )">
                        {{__("Delete")}}
                    </x-jet-danger-button>
                </div>
            </div>
        @endforeach

        @livewire('create-entity')

        @livewire('delete-entity')

    </x-slot>
</x-jet-action-section>
