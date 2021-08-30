<div>
    <div class="flex flex-wrap">
        @forelse($phoneCalls as $phoneCall)
            <div class="mt-2 mx-2">
                <x-audio-telephony :model="$phoneCall" :showContractor="true"/>
            </div>
        @empty
            <p>{{__('contractors.no_calls')}}</p>
        @endforelse
    </div>

    <div class="mt-5">
        {{ $phoneCalls->links() }}
    </div>


    <x-drawer max-width="max-w-md" :isOpen="$modalShowOneVisible" onClose="closeShowModal()">
        <div class="px-4 sm:px-6">
            <h2 class="text-lg font-medium text-gray-900">{{__('Contractor')}} {{$title}}</h2>
        </div>
        <div class="mt-6 relative flex-1 px-4 sm:px-6">
            <div class="absolute inset-0 px-4 sm:px-6">
                <div>
                    <x-jet-label value="{{__('contractors.name')}}" />
                    <x-jet-input disabled type="text" wire:model="title"/>
                    <x-jet-input-error for="title" class="mt-2" />
                </div>

                <div class="mt-2">
                    <x-jet-label value="{{__('contractors.phone_number')}}" />
                    <x-jet-input disabled type="text" class="phone" wire:model="phone"/>
                    <x-jet-input-error for="phone" class="mt-2" />
                </div>

                <div class="mt-2">
                    <x-jet-label value="E-mail" />
                    <x-jet-input disabled type="text" wire:model="email"/>
                    <x-jet-input-error for="email" class="mt-2" />
                </div>
            </div>
        </div>
    </x-drawer>
<div>
