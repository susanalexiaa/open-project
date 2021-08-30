<x-jet-form-section submit="updateCompanyInformation">
    <x-slot name="title">{{__('Settings')}}</x-slot>
    <x-slot name="description"></x-slot>
    <x-slot name="form">
        <!-- Name -->
        <div class="sm:col-span-4">
            <x-jet-label for="name" value="{{__('settings.name')}}" />
            <x-jet-input required id="name" type="text" class="mt-1 block w-full" wire:model.defer="name" autocomplete="name" />
            <x-jet-input-error for="name" class="mt-2" />
        </div>

        <!-- Email -->
        <div class="col-span-6 sm:col-span-4">
            <x-jet-label for="email" value="{{__('settings.phone_number')}}" />
            <x-jet-input required id="phone" type="text" class="mt-1 block w-full" wire:model.defer="phone" />
            <x-jet-input-error for="email" class="mt-2" />
        </div>
    </x-slot>

    <x-slot name="actions">
        <div class="text-left">
            <x-jet-button wire:loading.attr="disabled">
                {{__('Save')}}
            </x-jet-button>
        </div>
    </x-slot>
</x-jet-form-section>
