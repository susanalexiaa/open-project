<div class="bg-white bg-opacity-25 p-6">

    <div class="mt-10 sm:mt-0">
        <x-jet-action-section>
            <x-slot name="title">
                {{ __('Integrations') }}
            </x-slot>

            <x-slot name="description">
                {{ __('All of the integrations that are register.') }}
            </x-slot>

            <x-slot name="actions">
                <x-jet-button type="button" wire:loading.attr="disabled" wire:click="createShowModal">
                    {{ __('Creat') }}
                </x-jet-button>
            </x-slot>


            <!-- Team Member List -->
            <x-slot name="content">
                <div class="space-y-6">
                    @if ($data->count() < 1)
                        <div class="ml-4">{{ __('Not available') }}</div>
                    @else
                        @foreach ($data as $integration)
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="ml-4">{{ $integration->name }}</div>
                                </div>

                                <div class="flex items-center">
                                    <div class="flex divide-x divide-gray-400">

                                    </div>
                                    <div class="flex-initial px-2 text-right">
                                        <x-jet-button wire:click="updateShowModal('{{ $integration->id }}')" wire:loading.attr="disabled">
                                            {{ __('Update') }}
                                        </x-jet-button>
                                    </div>
                                    <div class="flex-initial px-2 text-right">
                                        <x-jet-danger-button wire:click="deleteShowModal('{{ $integration->id }}')" wire:loading.attr="disabled">
                                            {{ __('Delete') }}
                                        </x-jet-danger-button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
                <div class="mt-8">{{$data->links()}}</div>
            </x-slot>
        </x-jet-action-section>

    </div>

    <x-jet-dialog-modal wire:model="modalFormVisible">
        <x-slot name="title">
            {{ __('Create Integration') }} {{ $modalId }}
        </x-slot>

        <x-slot name="content">
            <div>
                <x-jet-label for="name" value="{{ __('Name') }}"/>
                <x-jet-input id="name" class="block mt-1 w-full" type="text" name="name" wire:model="name"
                             wire:model.defer="name"/>
                @error('name') <span class="error text-red-500">{{ $message }}</span> @enderror
            </div>

            <div class="mt-4">
                <x-jet-label for="type" value="{{ __('Type') }}"/>
                <select id="type" wire:model.defer="type" class="select select max-w-xs bg-gray-200">
                    <option selected value="Трекер почты">Трекер почты</option>
                </select>
                @error('type') <span class="error text-red-500">{{ $message }}</span> @enderror
            </div>

            <div class="mt-4">
                <x-jet-label for="operator" value="{{ __('Operator') }}"/>
                <select id="operator" wire:model.defer="operator_id" class="select select max-w-xs bg-gray-200">
                    @foreach($operators as $operator)
                        <option @if($loop->first) selected @endif value="{{ $operator->id }}">{{ $operator->address }}</option>
                    @endforeach
                </select>
                @error('operator_id') <span class="error text-red-500">{{ $message }}</span> @enderror
            </div>

            <div class="mt-4">
                <x-jet-label for="login" value="{{ __('Login data') }}"/>
                <x-jet-input id="login" class="block mt-1 w-full" type="text" wire:model.defer="login"/>
                @error('login') <span class="error text-red-500">{{ $message }}</span> @enderror
            </div>

            <div class="mt-4">
                <x-jet-label for="password" value="{{ __('Password') }}"/>
                <x-jet-input id="password" class="block mt-1 w-full" type="text" wire:model.defer="password"/>
                @error('password') <span class="error text-red-500">{{ $message }}</span> @enderror
            </div>

            <div class="mt-4">
                <x-jet-label for="allowed_addresses" value="{{ __('Allowed addresses') }}"/>
                <textarea wire:model.defer="allowed_addresses" id="allowed_addresses" class="w-full"></textarea>
                @error('allowed_addresses') <span class="error text-red-500">{{ $message }}</span> @enderror
            </div>


            <div class="mt-4">
                <x-jet-label for="responsible_id" value="{{ __('Responsible person') }}"/>
                @if( isset($responsible_id) )
                    <livewire:responsible-user-search
                        name="responsible_id"
                        placeholder="{{__('integrations.search_responsible_person')}}"
                        :searchable="true"
                        :value="$responsible_id"
                    />
                @else
                    <livewire:responsible-user-search
                        name="responsible_id"
                        placeholder="{{__('integrations.search_responsible_person')}}"
                        :searchable="true"
                    />
                @endif

                <x-jet-input-error for="responsible_id" class="mt-2"/>
            </div>


            <div class="mt-4">
                <x-jet-label value="{{ __('Team') }}"/>

                @if( isset($team_id) )
                    <livewire:team-search
                        name="team_id"
                        placeholder="{{__('integrations.search_team')}}"
                        :searchable="true"
                        :value="$team_id"
                    />

                @else
                <livewire:team-search
                        name="team_id"
                        placeholder="{{__('integrations.search_team')}}"
                        :searchable="true"
                    />
                @endif

                <x-jet-input-error for="team_id" class="mt-2"/>
            </div>

            <div class="mt-4">
                <x-jet-label for="datepicker-integration" value="{{ __('Date of last integration') }}"/>
                <x-jet-input id="datepicker-integration" class="block mt-1 w-full" type="text" wire:model.defer="last_integration"/>
                @error('last_integration') <span class="error text-red-500">{{ $message }}</span> @enderror
            </div>

            <div class="mt-4">
                <x-jet-label for="is_active" value="{{ __('Active') }}"/>
                <input type="checkbox" id="is_active" wire:model="is_active">
                @error('is_active') <span class="error text-red-500">{{ $message }}</span> @enderror
            </div>
        </x-slot>
        <x-slot name="footer">
            <x-jet-secondary-button wire:click="$toggle('modalFormVisible')" wire:loading.attr="disabled">
                {{ __('Exit') }}
            </x-jet-secondary-button>

            @if ($modalId)
                <x-jet-button class="ml-2" wire:click="update" wire:loading.attr="disabled">
                    {{ __('Update') }}
                </x-jet-button>
            @else
                <x-jet-button class="ml-2" wire:click="create" wire:loading.attr="disabled">
                    {{ __('Create') }}
                </x-jet-button>
            @endisset

        </x-slot>
    </x-jet-dialog-modal>

    <x-jet-confirmation-modal wire:model="confirmingDeletion">
        <x-slot name="title">
            {{ __('Delete Integration') }}
        </x-slot>

        <x-slot name="content">
            {{ __('Are you sure you want to delete this integration?') }}
        </x-slot>

        <x-slot name="footer">
            <x-jet-secondary-button wire:click="$toggle('confirmingDeletion')" wire:loading.attr="disabled">
                {{ __('Nevermind') }}
            </x-jet-secondary-button>

            <x-jet-danger-button class="ml-2" wire:click="delete" wire:loading.attr="disabled">
                {{ __('Delete') }}
            </x-jet-danger-button>
        </x-slot>
    </x-jet-confirmation-modal>
</div>
