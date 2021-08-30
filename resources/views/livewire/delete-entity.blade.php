<x-jet-confirmation-modal wire:model="confirmingEntityDeletion">
    <x-slot name="title">
        Удаление организации
    </x-slot>

    <x-slot name="content">
        Вы уверены? Все статистика и отзывы будут удалены
    </x-slot>

    <x-slot name="footer">
        <x-jet-secondary-button wire:click="$toggle('confirmingEntityDeletion')" wire:loading.attr="disabled">
            Отмена
        </x-jet-secondary-button>

        <x-jet-danger-button class="ml-2" wire:click="deleteEntity" wire:loading.attr="disabled">
            Удалить
        </x-jet-danger-button>
    </x-slot>
</x-jet-confirmation-modal>