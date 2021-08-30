<div>
    <x-drawer max-width="max-w-xl" :isOpen="$modalCreateBilling" onClose="closeModalCreateBilling()">
        <div class="px-4 sm:px-6">
            <h2 class="text-lg font-medium text-gray-900">
                Создать оплату
            </h2>
        </div>
        <div class="mt-6 relative flex-1 px-4 sm:px-6">
            <div class="absolute inset-0 px-4 sm:px-6">
                <form wire:submit.prevent="addBilling()">
                    @csrf
                    <div>
                        <x-jet-label for="billing_create_type" value="Тип операции" />
                        <select wire:model="type" required id="billing_create_type">
                            <option value="Наличная оплата">Наличная оплата</option>
                            <option value="Безналичная оплата">Безналичная оплата</option>
                            <option value="Эквайринговая операция">Эквайринговая операция</option>
                        </select>
                        <x-jet-input-error for="type" class="mt-2" />
                    </div>

                    <div class="mt-2">
                        <x-jet-label for="billing_create_date" value="Дата оплаты" />
                        <x-jet-input id="billing_create_date" wire:model="made_at" type="text" class="mt-1 block w-full datepickerWithTime" />
                        <x-jet-input-error for="made_at" class="mt-2" />
                    </div>

                    <div class="mt-2">
                        <x-jet-label for="billing_create_total" value="Сумма" />
                        <x-jet-input id="billing_create_total" required wire:model="total" type="text" class="mt-1 block w-full" />
                        <x-jet-input-error for="total" class="mt-2" />
                    </div>

                    <div class="text-left mt-4">
                        <x-jet-button>Добавить оплату</x-jet-button>
                    </div>
                </form>
            </div>
        </div>
    </x-drawer>

    <x-drawer max-width="max-w-xl" :isOpen="$modalEditBilling" onClose="closeModalEditBilling()">
        <div class="px-4 sm:px-6">
            <h2 class="text-lg font-medium text-gray-900">
                Редактирование оплаты #{{ $billing_id }}
            </h2>
        </div>
        <div class="mt-6 relative flex-1 px-4 sm:px-6">
            <div class="absolute inset-0 px-4 sm:px-6">
                <form wire:submit.prevent="saveBilling()">
                    @csrf
                    <div>
                        <x-jet-label for="billing_edit_type" value="Тип операции" />
                        <select wire:model="type" required id="billing_edit_type">
                            <option value="Наличная оплата">Наличная оплата</option>
                            <option value="Безналичная оплата">Безналичная оплата</option>
                            <option value="Эквайринговая операция">Эквайринговая операция</option>
                        </select>
                        <x-jet-input-error for="type" class="mt-2" />
                    </div>

                    <div class="mt-2">
                        <x-jet-label for="billing_create_date" value="Дата оплаты" />
                        <x-jet-input id="billing_create_date" wire:model="made_at" type="text" class="mt-1 block w-full datepickerWithTime" />
                        <x-jet-input-error for="made_at" class="mt-2" />
                    </div>

                    <div class="mt-2">
                        <x-jet-label for="billing_edit_total" value="Сумма" />
                        <x-jet-input id="billing_edit_total" required wire:model="total" type="text" class="mt-1 block w-full" autocomplete="fullname" />
                        <x-jet-input-error for="total" class="mt-2" />
                    </div>

                    <div class="text-left mt-4">
                        <x-jet-button>Сохранить</x-jet-button>
                    </div>
                </form>
            </div>
        </div>
    </x-drawer>
</div>