<div>

    @can('addTeamMember', $team)
        <x-jet-section-border />
        <!-- Add Team Member -->
            <div class="mt-10 sm:mt-0">
                <x-jet-form-section submit="addTeamMember">
                    <x-slot name="title">
                        {{ __('Add Team Member') }}
                    </x-slot>

                    <x-slot name="description">
                        {{ __('Add a new team member to your team, allowing them to collaborate with you.') }}
                    </x-slot>

                    <x-slot name="form">
                        <div class="col-span-6">
                            <div class="max-w-xl text-sm text-gray-600">
                                {{ __('Please provide the email address of the person you would like to add to this team. The email address must be associated with an existing account.') }}
                            </div>
                        </div>

                        <!-- Member Email -->
                        <div class="col-span-6 sm:col-span-4">
                            <x-jet-label for="email" value="{{ __('Email') }}" />
                            <x-jet-input id="name" type="text" class="mt-1 block w-full" wire:model.defer="addTeamMemberForm.email" />
                            <x-jet-input-error for="email" class="mt-2" />
                        </div>

                        <!-- Role -->
                        @if (count($this->roles) > 0)
                            <div class="col-span-6 lg:col-span-4">
                                <x-jet-label for="role" value="{{ __('Role') }}" />
                                <x-jet-input-error for="role" class="mt-2" />

                                <div class="mt-1 border border-gray-200 rounded-lg cursor-pointer">
                                    @foreach ($this->roles as $index => $role)
                                        <div class="px-4 py-3 {{ $index > 0 ? 'border-t border-gray-200' : '' }}"
                                             wire:click="$set('addTeamMemberForm.role', '{{ $role->key }}')">
                                            <div class="{{ isset($addTeamMemberForm['role']) && $addTeamMemberForm['role'] !== $role->key ? 'opacity-50' : '' }}">
                                                <!-- Role Name -->
                                                <div class="flex items-center">
                                                    <div class="text-sm text-gray-600 {{ $addTeamMemberForm['role'] == $role->key ? 'font-semibold' : '' }}">
                                                        {{ $role->name }}
                                                    </div>

                                                    @if ($addTeamMemberForm['role'] == $role->key)
                                                        <svg class="ml-2 h-5 w-5 text-green-400" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                    @endif
                                                </div>

                                                <!-- Role Description -->
                                                <div class="mt-2 text-xs text-gray-600">
                                                    {{ $role->description }}
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </x-slot>

                    <x-slot name="actions">
                        <x-jet-action-message class="mr-3" on="saved">
                            {{ __('Added.') }}
                        </x-jet-action-message>

                        <x-jet-button>
                            {{ __('Add') }}
                        </x-jet-button>
                    </x-slot>
                </x-jet-form-section>
            </div>
    @endcan


    @if ($team->allUsers()->isNotEmpty())
        <x-jet-section-border />

        <!-- Manage Team Members -->
        <div class="mt-10 sm:mt-0">
            <x-jet-action-section>
                <x-slot name="title">
                    {{ __('Team Members') }}
                </x-slot>

                <x-slot name="description">
                    {{ __('All of the people that are part of this team.') }}
                    @can('addTeamMember', $team)
                        <x-jet-button class="mt-3 mb-3 bg-blue-500 hover:bg-blue-400 focus:bg-blue-400 active:bg-blue-400" wire:click="createShowModal">
                            {{__("Create")}}
                        </x-jet-button>
                    @endcan

                </x-slot>


                <!-- Team Member List -->
                <x-slot name="content">
                    <div class="space-y-6">
                        @foreach ($team->allUsers()->sortBy('name') as $user)
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <img class="w-8 h-8 rounded-full" src="{{ $user->profile_photo_url }}" alt="{{ $user->name }}">
                                    <div class="ml-4">{{ $user->name }}</div>
                                </div>

                                <div class="flex items-center">
                                    @can('update', $user)
                                        <button class="ml-2 text-sm text-gray-400 underline" wire:click="updateShowModal('{{ $user->id }}')">
                                            {{__("Edit")}}
                                        </button>
                                    @endcan
                                    <!-- Manage Team Member Role -->
                                    @if($team->user_id === $user->id)
                                        <div class="ml-2 text-sm text-gray-400">
                                            {{__('teams.owner')}}
                                        </div>
                                    @else
                                        @if (Laravel\Jetstream\Jetstream::hasRoles())
                                            @can('addTeamMember', $team)
                                                <button class="ml-2 text-sm text-gray-400 underline" wire:click="manageRole('{{ $user->id }}')">
                                                    {{ Laravel\Jetstream\Jetstream::findRole($user->membership->role)->name }}
                                                </button>
                                            @else
                                                <span class="ml-2 text-sm text-gray-400 underline">
                                                    {{ Laravel\Jetstream\Jetstream::findRole($user->membership->role)->name }}
                                                </span>
                                            @endcan
                                        @endif
                                    @endif

                                <!-- Leave Team -->
                                    @if ($this->user->id === $user->id)

                                        <!-- Remove Team Member -->
                                    @elseif (Gate::check('removeTeamMember', $team) && $team->user_id !== $user->id)
                                        <button class="cursor-pointer ml-6 text-sm text-red-500 focus:outline-none" wire:click="confirmTeamMemberRemoval('{{ $user->id }}')">
                                            {{ __('Remove') }}
                                        </button>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </x-slot>
            </x-jet-action-section>
        </div>
@endif
    <x-jet-dialog-modal wire:model="createUserModal">
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
        </x-slot>
        <x-slot name="footer">
            <x-jet-secondary-button wire:click="$toggle('createUserModal')" wire:loading.attr="disabled">
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

<!-- Role Management Modal -->
    <x-jet-dialog-modal wire:model="currentlyManagingRole">
        <x-slot name="title">
            {{ __('Manage Role') }}
        </x-slot>

        <x-slot name="content">
            <div class="mt-1 border border-gray-200 rounded-lg cursor-pointer">
                @foreach ($this->roles as $index => $role)
                    <div class="px-4 py-3 {{ $index > 0 ? 'border-t border-gray-200' : '' }}"
                         wire:click="$set('currentRole', '{{ $role->key }}')">
                        <div class="{{ $currentRole !== $role->key ? 'opacity-50' : '' }}">
                            <!-- Role Name -->
                            <div class="flex items-center">
                                <div class="text-sm text-gray-600 {{ $currentRole == $role->key ? 'font-semibold' : '' }}">
                                    {{ $role->name }}
                                </div>

                                @if ($currentRole == $role->key)
                                    <svg class="ml-2 h-5 w-5 text-green-400" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                @endif
                            </div>

                            <!-- Role Description -->
                            <div class="mt-2 text-xs text-gray-600">
                                {{ $role->description }}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </x-slot>

        <x-slot name="footer">
            <x-jet-secondary-button wire:click="stopManagingRole" wire:loading.attr="disabled">
                {{ __('Nevermind') }}
            </x-jet-secondary-button>

            <x-jet-button class="ml-2" wire:click="updateRole" wire:loading.attr="disabled">
                {{ __('Save') }}
            </x-jet-button>
        </x-slot>
    </x-jet-dialog-modal>

    <!-- Leave Team Confirmation Modal -->
    <x-jet-confirmation-modal wire:model="confirmingLeavingTeam">
        <x-slot name="title">
            {{ __('Leave Team') }}
        </x-slot>

        <x-slot name="content">
            {{ __('Are you sure you would like to leave this team?') }}
        </x-slot>

        <x-slot name="footer">
            <x-jet-secondary-button wire:click="$toggle('confirmingLeavingTeam')" wire:loading.attr="disabled">
                {{ __('Nevermind') }}
            </x-jet-secondary-button>

            <x-jet-danger-button class="ml-2" wire:click="leaveTeam" wire:loading.attr="disabled">
                {{ __('Leave') }}
            </x-jet-danger-button>
        </x-slot>
    </x-jet-confirmation-modal>

    <!-- Remove Team Member Confirmation Modal -->
    <x-jet-confirmation-modal wire:model="confirmingTeamMemberRemoval">
        <x-slot name="title">
            {{ __('Remove Team Member') }}
        </x-slot>

        <x-slot name="content">
            {{ __('Are you sure you would like to remove this person from the team?') }}
        </x-slot>

        <x-slot name="footer">
            <x-jet-secondary-button wire:click="$toggle('confirmingTeamMemberRemoval')" wire:loading.attr="disabled">
                {{ __('Nevermind') }}
            </x-jet-secondary-button>

            <x-jet-danger-button class="ml-2" wire:click="removeTeamMember" wire:loading.attr="disabled">
                {{ __('Remove') }}
            </x-jet-danger-button>
        </x-slot>
    </x-jet-confirmation-modal>
</div>
