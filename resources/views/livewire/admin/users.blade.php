<div class="bg-white bg-opacity-25 p-6">

    {{--    data table--}}
    <div class="mt-10 sm:mt-0">
        <x-jet-action-section>
            <x-slot name="title">
                {{ __('Users') }}
            </x-slot>

            <x-slot name="description">
                {{ __('All of the people that are register.') }}
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
                        @foreach ($data as $user)
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <img class="w-8 h-8 rounded-full" src="{{ $user->profile_photo_url }}"
                                         alt="{{ $user->name }}">
                                    <div class="ml-4">{{ $user->name }}</div>
                                </div>

                                <div class="flex items-center">
                                    <div class="flex divide-x divide-gray-400">
                                        @foreach ($user->allTeams() as $team)
                                            <div class="flex-initial px-2 text-sm text-center text-gray-400">
                                                {{ $team->name }}
                                            </div>
                                        @endforeach


                                    </div>
                                    <div class="flex-initial px-2 text-right">
                                        <x-jet-button wire:click="updateShowModal('{{ $user->id }}')" wire:loading.attr="disabled">
                                            {{ __('Update') }}
                                        </x-jet-button>
                                    </div>
                                    <div class="flex-initial px-2 text-right">
                                        <x-jet-danger-button wire:click="deleteShowModal('{{ $user->id }}')" wire:loading.attr="disabled">
                                            {{ __('Delete') }}
                                        </x-jet-danger-button>
                                    </div>
                                    <!-- Manage Team Member Role -->
                                    {{--                                    @if (Gate::check('addTeamMember', $team) && Laravel\Jetstream\Jetstream::hasRoles())--}}
                                    {{--                                        <button class="ml-2 text-sm text-gray-400 underline"--}}
                                    {{--                                                wire:click="manageRole('{{ $user->id }}')">--}}
                                    {{--                                            {{ Laravel\Jetstream\Jetstream::findRole($user->membership->role)->name }}--}}
                                    {{--                                        </button>--}}
                                    {{--                                    @elseif (Laravel\Jetstream\Jetstream::hasRoles())--}}
                                    {{--                                        <div class="ml-2 text-sm text-gray-400">--}}
                                    {{--                                            {{ Laravel\Jetstream\Jetstream::findRole($user->membership->role)->name }}--}}
                                    {{--                                        </div>--}}
                                    {{--                                    @endif--}}

                                    {{--                            <!-- Leave Team -->--}}
                                    {{--                                @if ($this->user->id === $user->id)--}}
                                    {{--                                    <button class="cursor-pointer ml-6 text-sm text-red-500 focus:outline-none" wire:click="$toggle('confirmingLeavingTeam')">--}}
                                    {{--                                        {{ __('Leave') }}--}}
                                    {{--                                    </button>--}}

                                    {{--                                    <!-- Remove Team Member -->--}}
                                    {{--                                @elseif (Gate::check('removeTeamMember', $team))--}}
                                    {{--                                    <button class="cursor-pointer ml-6 text-sm text-red-500 focus:outline-none" wire:click="confirmTeamMemberRemoval('{{ $user->id }}')">--}}
                                    {{--                                        {{ __('Remove') }}--}}
                                    {{--                                    </button>--}}
                                    {{--                                @endif--}}
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
            {{ __('Create User') }} {{ $modalId }}
        </x-slot>
        <x-slot name="content">
            <div>
                <x-jet-label for="name" value="{{ __('Name') }}"/>
                <x-jet-input id="name" class="block mt-1 w-full" type="text" name="name" wire:model="name"
                             wire:model.defer="name"/>
                @error('name') <span class="error text-red-500">{{ $message }}</span> @enderror
            </div>

            <div class="mt-4">
                <x-jet-label for="email" value="{{ __('Email') }}"/>
                <x-jet-input id="email" class="block mt-1 w-full" type="email" name="email" wire:model="email"
                             wire:model.defer="email"/>
                @error('email') <span class="error text-red-500">{{ $message }}</span> @enderror
            </div>
            <div class="mt-4">
                <x-jet-label for="position" value="{{ __('Position') }}"/>
                <x-jet-input id="position" class="block mt-1 w-full" type="text" name="position" wire:model="position"
                             wire:model.defer="position"/>
                @error('position') <span class="error text-red-500">{{ $message }}</span> @enderror
            </div>
            @if (!$modalId)
                <div class="mt-4">
                    <x-jet-label for="email" value="{{ __('Team') }}"/>
                    <x-select class="w-full" name="team_id" wire:model="team_id" wire:model.defer="team_id">
                        @foreach($this->teams as $team)
                            <option value="{{$team->id}}">{{$team->name}}</option>
                        @endforeach
                    </x-select>
                    @error('team') <span class="error text-red-500">{{ $message }}</span> @enderror
                </div>
            @endif


            <div class="mt-4">
                <x-jet-label for="password" value="{{ __('Password') }}"/>
                <x-jet-input id="password" class="block mt-1 w-full" type="password" name="password"
                             wire:model="password" wire:model.defer="password"/>
                @error('password') <span class="error text-red-500">{{ $message }}</span> @enderror
            </div>

            <div class="mt-4">
                <x-jet-label for="password_confirmation" value="{{ __('Confirm Password') }}"/>
                <x-jet-input id="password_confirmation" class="block mt-1 w-full" type="password"
                             name="password_confirmation" wire:model="password_confirmation"
                             wire:model.defer="password_confirmation"/>
                @error('password_confirmation') <span class="error text-red-500">{{ $message }}</span> @enderror
            </div>
            <div class="mt-3">
                <x-jet-label class="inline-block">
                    <x-jet-checkbox wire:model="recording_allowed">

                    </x-jet-checkbox>
                    Разрешить запись в мобильном приложении
                </x-jet-label>

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

    <!-- Delete User Confirmation Modal -->
    <x-jet-confirmation-modal wire:model="confirmingUserDeletion">
        <x-slot name="title">
            {{ __('Delete User') }}
        </x-slot>

        <x-slot name="content">
            {{ __('Are you sure you want to delete this user?') }}
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
