<div>
    <x-drawer max-width="max-w-md" :isOpen="$modalChooseContractor" onClose="closeModalChooseContractor()">
        <div class="px-4 sm:px-6">
            <h2 class="text-lg font-medium text-gray-900">{{__('contractors.add_contractor')}}</h2>
        </div>
        <div class="mt-6 relative flex-1 px-4 sm:px-6">
            <div class="absolute inset-0 px-4 sm:px-6">
                <livewire:contractors-search-dropdown
                name="contractor_id"
                placeholder="{{__('contractors.search_contractor')}}"
                :searchable="true"
                />

                <div class="text-left space-y-1 mt-4">
                    <x-jet-button class="mr-1" wire:click="saveChosenContractor()">{{__('contractors.add_contractor')}}</x-jet-button>
                    <x-jet-button wire:click="openModalCreateContractor()">{{__('contractors.create_contractor')}}</x-jet-button>
                </div>

                <div class="mt-4">
                    <h3 class="text-lg font-medium text-gray-900">{{__('contractors.frequently_used')}}</h3>
                    @foreach( $usable_contractors as $usable_contractor )
                        <p class="py-1 cursor-pointer" wire:click="chooseContractorFromMostUsable({{ $usable_contractor->id }})" >{{ $usable_contractor->title }}</p>
                    @endforeach
                </div>
            </div>
        </div>
    </x-drawer>

    <x-drawer max-width="max-w-xl" :isOpen="$modalCreateContractor" onClose="closeModalCreateContractor()">
        <div class="px-4 sm:px-6">
            <h2 class="text-lg font-medium text-gray-900">
                {{__('contractors.create_contractor')}}
            </h2>
        </div>
        <div class="mt-6 relative flex-1 px-4 sm:px-6">
            <div class="absolute inset-0 px-4 sm:px-6">
                <form wire:submit.prevent="addContractor()">
                    @csrf
                    <div class="sm:col-span-4">
                        <x-jet-label for="title" value="{{__('contractors.name')}}" />
                        <x-jet-input id="title" required wire:model="title" type="text" class="mt-1 block w-full" autocomplete="fullname" />
                        <x-jet-input-error for="fullname" class="mt-2" />
                    </div>
                    <div class="col-span-6 sm:col-span-4 mt-2">
                        <x-jet-label for="phone" value="{{__('contractors.phone_number')}}" />
                        <x-jet-input id="phone" name="phone" wire:model="phone" placeholder="+Х ХХХ ХХХ ХХ ХХ" type="text" class="mt-1 block w-full" />
                        <x-jet-input-error for="phone" class="mt-2" />
                    </div>
                    <div class="col-span-6 sm:col-span-4 mt-2">
                        <x-jet-label for="email_form" value="E-mail" />
                        <x-jet-input id="email_form" wire:model="email" type="email" class="mt-1 block w-full" />
                        <x-jet-input-error for="email" class="mt-2" />
                    </div>
                    <div class="text-left mt-4">
                        <x-jet-button :disabled="isset($duplicates) && count($duplicates)>0">{{__('contractors.add_contractor')}}</x-jet-button>
                    </div>
                </form>

                @if( isset($duplicates) && count($duplicates))

                <h3 class="text-lg font-bold text-gray-900 mt-2">Найдены дубли</h3>

                    @foreach($duplicates as $duplicate)
                        <div class="flex items-center justify-between my-1">
                            <p>{{ $duplicate->title }}</p>
                            <x-jet-button wire:click="chooseContractorFromDublicates({{ $duplicate->id }})">Выбрать</x-jet-button>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </x-drawer>

    <x-drawer max-width="max-w-xl" :isOpen="$modalEditContractor" onClose="closeModalEditContractor()">
        <div class="px-4 sm:px-6">
            <h2 class="text-lg font-medium text-gray-900">
                Редактировать контрагента {{$title}}
            </h2>
        </div>
        <div class="mt-6 relative flex-1 px-4 sm:px-6">
            <div class="absolute inset-0 px-4 sm:px-6">
                <form wire:submit.prevent="editContractor()">
                    @csrf
                    <div class="sm:col-span-4">
                        <x-jet-label for="title" value="Наименование" />
                        <x-jet-input id="title" required wire:model="title" type="text" class="mt-1 block w-full" autocomplete="fullname" />
                        <x-jet-input-error for="fullname" class="mt-2" />
                    </div>
                    <div class="col-span-6 sm:col-span-4 mt-2">
                        <x-jet-label for="phone" value="Номер телефона" />
                        <x-jet-input id="phone" name="phone" wire:model="phone" placeholder="+Х ХХХ ХХХ ХХ ХХ" type="text" class="mt-1 block w-full" />
                        <x-jet-input-error for="phone" class="mt-2" />
                    </div>
                    <div class="col-span-6 sm:col-span-4 mt-2">
                        <x-jet-label for="email_form" value="E-mail" />
                        <x-jet-input id="email_form" wire:model="email" type="email" class="mt-1 block w-full" />
                        <x-jet-input-error for="email" class="mt-2" />
                    </div>
                    <div class="text-left mt-4">
                        <x-jet-button>Сохранить</x-jet-button>
                    </div>
                </form>
            </div>
        </div>
    </x-drawer>
</div>
