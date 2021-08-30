<div class="bg-white bg-opacity-25 p-6">
    <div class="mt-10 sm:mt-0">
        <x-jet-action-section>
            <x-slot name="title">
                {{ __('Statuses') }}
            </x-slot>

            <x-slot name="description">
                {{ __('All of the statuses that are register.') }}
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
                        @foreach ($data as $status)
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 rounded-full bg-{{$status->color_class}}"></div>
                                    <div class="ml-4">{{ $status->name }}</div>
                                </div>

                                <div class="flex items-center">
                                    <div class="flex-initial px-2 text-right">
                                        <x-jet-button wire:click="updateShowModal('{{ $status->id }}')" wire:loading.attr="disabled">
                                            {{ __('Update') }}
                                        </x-jet-button>
                                    </div>
                                    <div class="flex-initial px-2 text-right">
                                        <x-jet-danger-button wire:click="deleteShowModal('{{ $status->id }}')" wire:loading.attr="disabled">
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
            {{ __('Create status') }} {{ $modalId }}
        </x-slot>
        <x-slot name="content">
            <div>
                <x-jet-label for="name" value="{{ __('Status') }}"/>
                <x-jet-input id="name" class="block mt-1 w-full" type="text" wire:model="name"/>
                @error('name') <span class="error text-red-500">{{ $message }}</span> @enderror
            </div>

            <div class="mt-4">
                <x-jet-label for="color_class" value="{{ __('Tailwind`s class') }}"/>
                <x-jet-input id="color_class" class="block mt-1 w-full" type="text" wire:model="color_class"/>
                @error('color_class') <span class="error text-red-500">{{ $message }}</span> @enderror
            </div>

            <div>
                <x-jet-label for="order" value="{{ __('Order') }}"/>
                <x-jet-input id="order" class="block mt-1 w-full" type="number" wire:model="order"/>
                @error('order') <span class="error text-red-500">{{ $message }}</span> @enderror
            </div>

            <div>
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

    <x-jet-confirmation-modal wire:model="confirmingUserDeletion">
        <x-slot name="title">
            {{ __('Delete Status') }}
        </x-slot>

        <x-slot name="content">
            {{ __('Are you sure you want to delete this status?') }}
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
</div>