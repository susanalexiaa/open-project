<div wire:poll.5000ms>
    <div class="flex flex-col md:flex-row justify-between space-x-0 md:space-x-2 space-y-2 md:space-y-0 items-center">
        <a href="#" wire:click="openModalNewLeadVisible()">
            <x-jet-button>{{__('Add')}}</x-jet-button>
        </a>
        <x-jet-input class="mb-1" placeholder="{{__('Search')}}..." wire:change="searchByKey($event.target.value)"
                     value="{{ $key_search }}" type="text"/>

        <x-jet-input class="ml-1 datepicker inline-block mb-1" type="text" placeholder="{{__('Date from')}}"
                     value="{{ $date_start }}" wire:change="searchByDateStart($event.target.value)"/>
        <x-jet-input class="ml-1 datepicker inline-block mb-1" type="text" placeholder="{{__('Date to')}}" value="{{ $date_end }}"
                     wire:change="searchByDateEnd($event.target.value)"/>

        <select wire:change="sortByStatus($event.target.value)" wire:model="status_search"
                class="select select max-w-xs bg-gray-200">
            <option value="-1">Выберите статус</option>
            @foreach($statuses as $status)
                <option value="{{ $status->id }}">{{ $status->name }}</option>
            @endforeach
        </select>

        <x-jet-button wire:click="resetFilters()">{{__('Reset')}}</x-jet-button>
    </div>
    <div class="mt-2">
        <div class="flex items-center mb-2">
            @if( $leads->count() )
                <x-jet-checkbox wire:model="checkLeadsOnPage" wire:change="toggleCheckLeadsOnPage()"/>
                <x-select-mass-action/>
            @endif
        </div>

        <div class="space-y-2">
            @forelse($leads as $lead_one)
                @include('partials.lead-list-view', ['lead' => $lead_one])
            @empty
                <p>Нет данных</p>
            @endforelse
        </div>

        @if( $leads->count() )
            <div class="mt-2">
                {{__('Mark all')}}
                <x-jet-checkbox wire:model="checkAllLeads" wire:change="toggleAllCheckLeads($event.target.value)"/>

                <x-select-mass-action/>
            </div>
        @endif

        <div class="mt-2">{{ $leads->links()  }}</div>

        @if( $leads->count() )
            <x-jet-confirmation-modal wire:model="confirmingUserDeletion">
                <x-slot name="title">{{ __('Delete Lead') }}</x-slot>

                <x-slot name="content">{{ __('Are you sure you want to delete this lead?') }}</x-slot>

                <x-slot name="footer">
                    <x-jet-secondary-button wire:click="$toggle('confirmingUserDeletion')" wire:loading.attr="disabled">
                        {{ __('Nevermind') }}
                    </x-jet-secondary-button>

                    <x-jet-danger-button class="ml-2" wire:click="delete" wire:loading.attr="disabled">
                        {{ __('Delete') }}
                    </x-jet-danger-button>
                </x-slot>
            </x-jet-confirmation-modal>


            <x-jet-confirmation-modal wire:model="confirmingMassDeletion">
                <x-slot name="title">{{ __('Delete Leads') }}</x-slot>

                <x-slot name="content">{{ __('Are you sure you want to delete these leads?') }}</x-slot>

                <x-slot name="footer">
                    <x-jet-secondary-button wire:click="$toggle('confirmingMassDeletion')" wire:loading.attr="disabled">
                        {{ __('Nevermind') }}
                    </x-jet-secondary-button>

                    <x-jet-danger-button class="ml-2" wire:click="deleteChecked" wire:loading.attr="disabled">
                        {{ __('Delete') }}
                    </x-jet-danger-button>
                </x-slot>
            </x-jet-confirmation-modal>

            <x-jet-confirmation-modal wire:model="confirmingMassStatusUpdate">
                <x-slot name="title">
                    {{ __('Update lead statuses') }}
                </x-slot>

                <x-slot name="content">
                    {{ __('Are you sure you want to update statuses of these leads?') }}

                    <div>
                        <p>Новый статус:</p>
                        <select wire:model="newStatus" class="select select max-w-xs bg-gray-200">
                            <option value="-1">Выберите статус</option>
                            @foreach($statuses as $status)
                                <option value="{{ $status->id }}">{{ $status->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </x-slot>

                <x-slot name="footer">
                    <x-jet-secondary-button wire:click="$toggle('confirmingMassStatusUpdate')"
                                            wire:loading.attr="disabled">
                        {{ __('Nevermind') }}
                    </x-jet-secondary-button>

                    <x-jet-button class="ml-2" wire:click="updateStatusesOfCheckedLeads" wire:loading.attr="disabled">
                        {{ __('Обновить') }}
                    </x-jet-button>
                </x-slot>
            </x-jet-confirmation-modal>


            <x-jet-confirmation-modal wire:model="modalLeadInProcess">
                <x-slot name="title"> Заявка открыта
                    пользователем @isset($userOpenedTheLead) {{ $userOpenedTheLead->name }} @endisset </x-slot>

                <x-slot name="content"></x-slot>

                <x-slot name="footer">
                    <x-jet-button class="ml-2" wire:click="closeModalLeadInProcess" wire:loading.attr="disabled">
                        {{ __('Ok') }}
                    </x-jet-button>
                </x-slot>
            </x-jet-confirmation-modal>

            @if( $modalFormVisible )
                <x-drawer max-width="max-w-screen-xl" :isOpen="$modalFormVisible" onClose="closeFormVisible()">
                    <div class="px-4 sm:px-6 flex justify-between">
                        @isset($lead)
                            <h2 class="text-lg font-medium text-gray-900">
                                Заявка {{$lead->id}}
                            </h2>
                            <div class="text-gray-500">
                                @displayDate($lead->created_at, 'd.m.Y H:i')
                            </div>
                        @endisset
                    </div>
                    <div class="mt-2 relative flex-1 px-4 sm:px-6">
                        <div class="absolute inset-0 px-4 sm:px-6">
                            @include('partials.leadform', compact('lead', 'statuses', 'sources', 'contractors'))
                        </div>
                    </div>
                </x-drawer>
            @endif
        @endif

        @if( $modalNewLeadVisible )
            <x-drawer max-width="max-w-4xl" :isOpen="$modalNewLeadVisible" onClose="closeModalNewLeadVisible()">
                <div class="px-4 sm:px-6">
                    <h2 class="text-lg font-medium text-gray-900">
                        {{__('leads.new_lead')}}
                    </h2>
                </div>
                <div class="mt-6 relative flex-1 px-4 sm:px-6">
                    <div class="absolute inset-0 px-4 sm:px-6">
                        @include('partials.leadform', compact('lead', 'statuses', 'sources', 'contractors'))
                    </div>
                </div>
            </x-drawer>
        @endif


        @livewire('contractors')
        @livewire('billings')

        @if( $showMessageNewLeads )
            <div class="bg-green-100 p-5 w-full sm:w-1/5 rounded fixed top-1 right-1 z-50">
                <div class="flex justify-between">
                    <div class="flex space-x-3">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                             class="flex-none fill-current text-green-500 h-4 w-4">
                            <path
                                d="M12 0c-6.627 0-12 5.373-12 12s5.373 12 12 12 12-5.373 12-12-5.373-12-12-12zm-1.25 16.518l-4.5-4.319 1.396-1.435 3.078 2.937 6.105-6.218 1.421 1.409-7.5 7.626z"/>
                        </svg>
                        <div class="flex-1 leading-tight text-sm text-green-700 font-medium">Добавлены новые заявки
                        </div>
                    </div>
                    <svg wire:click="closeMessageNewLeads()" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                         class="flex-none fill-current text-green-600 h-3 w-3 cursor-pointer">
                        <path
                            d="M23.954 21.03l-9.184-9.095 9.092-9.174-2.832-2.807-9.09 9.179-9.176-9.088-2.81 2.81 9.186 9.105-9.095 9.184 2.81 2.81 9.112-9.192 9.18 9.1z"/>
                    </svg>
                </div>
            </div>
        @endif

    </div>
    <div class="hidden">
        <x-vendor.nomenclature-searcher>

        </x-vendor.nomenclature-searcher>
    </div>
</div>
