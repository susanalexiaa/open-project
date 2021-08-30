<x-guest-layout>
    <div class="py-5 bg-gray-100">
        <div class="mx-2 sm:px-6 lg:px-8">

            <div class="p-6 bg-white shadow rounded-lg">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Форма обратной связи <br>
                    {{ $entity->name }}
                </h2>
            </div>

            <form class="add-feedback" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="p-6 mt-3 bg-white shadow rounded-lg">
                    <x-jet-label for="name" value="ФИО" />
                    <x-jet-input id="name" name="name" required type="text" class="mt-1 block w-full" autocomplete="name" value="{{ old('name') }}" />
                    <x-jet-input-error for="name" class="mt-2" />
                </div>
                <div class="p-6 mt-3 bg-white shadow rounded-lg">
                    <x-jet-label for="phone" value="Номер телефона" />
                    <x-jet-input id="phone" type="text" required placeholder="+Х ХХХ ХХХ ХХ ХХ" class="mt-1 block w-full" name="phone" value="{{ old('phone') }}" autocomplete="phone" />
                    <x-jet-input-error for="phone" class="mt-2" />
                </div>

                <div class="p-6 mt-3 bg-white shadow rounded-lg service_quality">
                    <x-jet-label value="Качество сервиса" />
                    <div id="rateYo"></div>
                    <x-jet-input id="rating" type="hidden" name="rating" class="mt-1 block w-full"/>
                    <x-jet-input-error for="rating" class="mt-2" />
                </div>

                <div class="p-6 mt-3 bg-white shadow rounded-lg">
                    <x-jet-label value="Комментарий" />
                    <textarea name="comment" class="rounded border-gray-300 w-full py-2 px-3 leading-tight"></textarea>
                    <x-jet-input-error for="comment" class="mt-2" />
                </div>

                <div class="p-6 mt-3 bg-white shadow rounded-lg">
                    <x-jet-label value="Приложить фото" />
                    
                    <label for="photo" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:text-gray-800 active:bg-gray-50 transition ease-in-out duration-150">
                        Добавить фото
                    </label>

                    <input type="file" multiple accept="image/*" id="photo" type="file" name="photo[]" class="hidden"> 
                    <x-jet-input-error for="photo" class="mt-2" />

                    <div class="photos_name">
                        
                    </div>
                </div>

                <div class="mt-2 text-center">
                    <x-jet-button type="submit">
                        Отправить
                    </x-jet-button>
                </div>
            </form>

        </div>
    </div>
</x-guest-layout>


