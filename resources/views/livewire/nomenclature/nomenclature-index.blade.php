<div>
    <div class="mt-10 sm:mt-0">
        <div class="md:grid md:grid-cols-4 md:gap-6">
            <div class="md:col-span-1 bg-white shadow sm:rounded-lg">
                <div class="px-4 py-5">
                    <h3 class="text-lg font-medium text-gray-900">{{ __('nomenclatures.categories') }}</h3>
                    <p class="mt-1 text-sm text-gray-600">
                    <div>
                        <x-jet-nav-link
                            :active="empty($activeCategory)"
                            href="javascript:void(0);"
                            wire:click="setActiveCategory(0)"
                        >
                            Все категории
                        </x-jet-nav-link>
                    </div>
                    @foreach($categories as $category)
                        <div wire:ignore>
                            @livewire('nomenclature.nomenclature-index-category', ['category' => $category, 'activeCategory' => $activeCategory])
                        </div>
                    @endforeach
                        <p class="mt-4 text-sm">
                            <x-jet-button
                                wire:click="$emit('openCategoryEditModal', 0);">
                                {{ __('Creat') }}
                            </x-jet-button>
                        </p>
                </div>
            </div>

            <div class="mt-5 md:mt-0 md:col-span-3">
                <div class="px-4 py-5 sm:p-6 bg-white shadow sm:rounded-lg">
                    <div class="space-y-6">
                        @if ($nomenclatures->count() < 1)
                            <div class="ml-4">{{ __('Not available records') }}</div>
                        @else
                            @foreach ($nomenclatures as $nomenclature)
                                <div class="flex items-center justify-between flex-wrap">
                                    <div class="flex items-center">
                                        <div class="ml-4">{{ $nomenclature->name }}</div>
                                    </div>
                                    <div class="flex items-center">
                                        <div class="flex-initial px-2 text-right">
                                            <x-button-link
                                                href="{{route('nomenclatures.edit', $nomenclature)}}">
                                                {{ __('nomenclatures.edit') }}
                                            </x-button-link>
                                        </div>
                                        <div class="flex-initial px-2 text-right">
                                            <form action="{{route('nomenclatures.destroy', $nomenclature)}}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <x-jet-button class="bg-red-600 text-white hover:bg-red-500 focus:border-red-700 focus:shadow-outline-red active:bg-red-600"
                                                              type="submit">
                                                    {{ __('Delete') }}
                                                </x-jet-button>
                                            </form>

                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                    <div class="mt-8">{{$nomenclatures->links()}}</div>
                </div>
            </div>
        </div>

    </div>
    @livewire('components.nomenclature.nomenclature-category-edit')
</div>
