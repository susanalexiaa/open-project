<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Добавление заявки
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-jet-action-section>
                <x-slot name="title">Добавление заявки</x-slot>
                <x-slot name="description"></x-slot>

                <x-slot name="content">
                    <form method="POST">
                        @csrf
                        <div class="sm:col-span-4">
                            <x-jet-label for="fullname" value="Заказчик" />
                            <x-jet-input id="fullname" name="fullname" type="text" class="mt-1 block w-full" autocomplete="fullname" />
                            <x-jet-input-error for="fullname" class="mt-2" />
                        </div>

                        <div class="col-span-6 sm:col-span-4 mt-2">
                            <x-jet-label for="phone" value="Номер телефона" />
                            <x-jet-input id="phone" placeholder="+Х ХХХ ХХХ ХХ ХХ" name="phone" type="text" class="mt-1 block w-full" />
                            <x-jet-input-error for="phone" class="mt-2" />
                        </div>

                        <div class="col-span-6 sm:col-span-4 mt-2">
                            <x-jet-label for="email_form" value="E-mail" />
                            <x-jet-input id="email_form" name="email" type="email" class="mt-1 block w-full" />
                            <x-jet-input-error for="email" class="mt-2" />
                        </div>

                        <div class="col-span-6 sm:col-span-4 mt-2">
                            <x-jet-label for="source" value="Источник" />
                            <select class="w-full bg-white rounded px-3 py-2" required id="source" name="source">
                                <option class="py-1" value="Реклама">Реклама</option>
                            </select>
                            <x-jet-input-error for="source" class="mt-2" />
                        </div>

                        <div class="col-span-6 sm:col-span-4 mt-2">
                            <x-jet-label for="status_id" value="Статус" />
                                <select class="w-full bg-white rounded px-3 py-2" required id="status_id" name="status_id">
                                    @foreach($statuses as $status)
                                        <option class="py-1" value="{{ $status->id }}">{{ $status->name }}</option>
                                    @endforeach
                                </select>
                            <x-jet-input-error for="status_id" class="mt-2" />
                        </div>

                        <div class="col-span-6 sm:col-span-4 mt-2">
                            <x-jet-label value="Описание" />
                            @trix(\App\Models\Lead::class, 'description')
                            <x-jet-input-error for="lead-trixFields.description" class="mt-2" />
                        </div>

                        <div class="text-left mt-4">
                            <x-jet-button>
                                Сохранить
                            </x-jet-button>
                        </div>
                    </form>
                </x-slot>

                <x-slot name="actions">
                    
                </x-slot>
            </x-jet-action-section>
        </div>
    </div>
</x-app-layout>