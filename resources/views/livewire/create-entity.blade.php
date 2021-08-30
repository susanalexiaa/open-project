<x-jet-dialog-modal wire:model="isAddEntityModalOpen">
    <x-slot name="title">
        {{__('entities.create')}}
    </x-slot>

    <x-slot name="content">
        <x-jet-label for="name" value="{{__('entities.name')}}" class="mt-4" />
        <x-jet-input wire:model="name" type="text" class="px-4 py-2 rounded font-mono text-sm text-gray-500 w-full"/>
    </x-slot>

    <x-slot name="footer">
        <x-jet-button wire:click="addEntity" wire:loading.attr="disabled">
            {{__('Create')}}
        </x-jet-button>

        <x-jet-secondary-button wire:loading.attr="disabled" wire:click="closeAddEntityModal">
            {{__('Close')}}
        </x-jet-secondary-button>
    </x-slot>
</x-jet-dialog-modal>
