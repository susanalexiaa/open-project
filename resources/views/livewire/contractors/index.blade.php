<div>
    <div class="max-w-7xl mx-auto flex justify-between ">
        <div>
            <a href="#" wire:click="$toggle('modalNewOneVisible')">
                <x-jet-button>{{__('Add')}}</x-jet-button>
            </a>
            <x-jet-input placeholder="{{__('Search')}}..." wire:change="render()" wire:model="key_search" type="text" class="mt-1"/>
        </div>
    </div>
    <div class="mt-2">
            @forelse($contractors as $contractor)
            <div class="px-6 py-5 mt-3 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="flex items-center">
                    <div class="w-full md:w-4/5 flex mb-2 flex-col">
                        <div class="w-full flex space-x-0 md:space-x-4 space-y-1 md:space-y-0 justify-start md:justify-between md:flex-nowrap flex-wrap items-center">
                            <div class="w-full md:w-1/6">{{ $contractor->title }}</div>
                            <div class="w-full md:w-1/6">{{ $contractor->phone }}</div>
                            <div class="w-full md:w-1/6">{{ $contractor->email }}</div>
                        </div>
                    </div>

                    <div class="w-full md:w-1/5 text-left space-x-1 space-y-1 md:text-right">
                        <x-jet-button wire:click="editShowModal('{{ $contractor->id }}')">
                            {{__('Edit')}}
                        </x-jet-button>

                        <x-jet-danger-button wire:click="deleteShowModal('{{ $contractor->id }}')" wire:loading.attr="disabled">
                            {{__('Delete')}}
                        </x-jet-danger-button>
                    </div>
                </div>
                <div class="w-full flex flex-wrap">
                    @foreach( $contractor->phoneCalls as $phoneCall)
                        <div style="width: 180px;" class="mx-2 my-2">
                            <x-audio-telephony :model="$phoneCall"/>
                        </div>
                    @endforeach
                </div>
            </div>
            @empty
                <p>{{__('No data available')}}</p>
            @endforelse

            <div class="mt-2">{{ $contractors->links()  }}</div>
    </div>

    <x-jet-confirmation-modal wire:model="confirmingUserDeletion">
        <x-slot name="title">
            {{ __('Delete Contractor') }}
        </x-slot>

        <x-slot name="content">
            {{ __('Are you sure you want to delete this contractor?') }}
        </x-slot>

        <x-slot name="footer">
            <x-jet-secondary-button wire:click="$toggle('confirmingUserDeletion')" wire:loading.attr="disabled">
                {{ __('Nevermind') }}
            </x-jet-secondary-button>

            <x-jet-danger-button class="ml-2" wire:click="delete" wire:loading.attr="disabled">
                {{ __('Delete') }}
            </x-jet-danger-button>
        </x-slot>
    </x-jet-confirmation-modal>

    <x-drawer max-width="max-w-md" :isOpen="$modalNewOneVisible" onClose="$toggle('modalNewOneVisible')">
        <div class="px-4 sm:px-6">
            <h2 class="text-lg font-medium text-gray-900">{{__('contractors.new')}}</h2>
        </div>
        <div class="mt-6 relative flex-1 px-4 sm:px-6">
            <div class="absolute inset-0 px-4 sm:px-6">
                <form wire:submit.prevent="createOne()">
                    @csrf
                    <div>
                        <x-jet-label for="name_new" value="{{__('contractors.name')}}" />
                        <x-jet-input type="text" wire:model="title" required/>
                        <x-jet-input-error for="title" class="mt-2" />
                    </div>

                    <div class="mt-2">
                        <x-jet-label for="phone_new" value="{{__('contractors.phone_number')}}" />
                        <x-jet-input type="text" class="phone" wire:model="phone" required/>
                        <x-jet-input-error for="phone" class="mt-2" />
                    </div>

                    <div class="mt-2">
                        <x-jet-label for="phone_new" value="E-mail" />
                        <x-jet-input type="text" wire:model="email" required/>
                        <x-jet-input-error for="email" class="mt-2" />
                    </div>

                    <div class="text-left mt-4">
                        <x-jet-button>{{__('Save')}}</x-jet-button>
                    </div>
                </form>
            </div>
        </div>
    </x-drawer>

    <x-drawer max-width="max-w-md" :isOpen="$modalEditOneVisible" onClose="editHideModal()">
        <div class="px-4 sm:px-6">
            <h2 class="text-lg font-medium text-gray-900">{{__('Contractor')}} {{$title}}</h2>
        </div>
        <div class="mt-6 relative flex-1 px-4 sm:px-6">
            <div class="absolute inset-0 px-4 sm:px-6">
                <form wire:submit.prevent="updateOne()">
                    @csrf
                    <div>
                        <x-jet-label for="name_new" value="{{__('contractors.name')}}" />
                        <x-jet-input type="text" wire:model="title" required/>
                        <x-jet-input-error for="title" class="mt-2" />
                    </div>

                    <div class="mt-2">
                        <x-jet-label for="phone_new" value="{{__('contractors.phone_number')}}" />
                        <x-jet-input type="text" class="phone" wire:model="phone" required/>
                        <x-jet-input-error for="phone" class="mt-2" />
                    </div>

                    <div class="mt-2">
                        <x-jet-label for="phone_new" value="E-mail" />
                        <x-jet-input type="text" wire:model="email" required/>
                        <x-jet-input-error for="email" class="mt-2" />
                    </div>

                    <div class="text-left mt-4">
                        <x-jet-button>{{__('Save')}}</x-jet-button>
                    </div>
                </form>
            </div>
        </div>
    </x-drawer>
</div>
