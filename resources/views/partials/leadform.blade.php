<div role="tab-container">
    <div role="tabs" class="flex">
        <div role="tab" class="flex-1 tab @if($this->activeTab === 'main') active @endif" data-id="#main" wire:click="$set('activeTab', 'main')">
            Основное
        </div>
        @if($this->lead->exists ?? false)
            <div role="tab" class="flex-1 tab border-1 @if($this->activeTab === 'products') active @endif" data-id="#products" wire:click="$set('activeTab', 'products')">
                Товары и услуги
            </div>
        @endif
        @if($this->lead->exists ?? false)
            <div role="tab" class="flex-1 tab border-1 @if($this->activeTab === 'calls') active @endif" data-id="#calls" wire:click="$set('activeTab', 'calls')">
                Звонки и письма
            </div>
        @endif
    </div>
    <div role="tabpanels">
        <div role="tabpanel" class="@if($this->activeTab === 'main') active @endif" id="main">
            <form class="pb-2"
                  wire:submit.prevent=@if(isset($lead))"update(Object.fromEntries(new FormData($event.target)))" @else "create(Object.fromEntries(new FormData($event.target)))" @endif>

            @csrf
            <div class="flex flex-col md:flex-row justify-between space-y-4 md:space-x-4 md:space-y-0">
                <div class="w-full md:flex-1">
                    <div class="flex flex-col md:flex-row justify-between space-y-2 md:space-x-2 md:space-y-0">
                        <div class="flex-1">
                            <x-jet-label for="status_id" value="{{__('leads.status')}}"/>
                            <select class="w-full bg-white rounded px-3 py-2" required id="status_id" name="status_id">
                                @if( isset($lead) && !$lead->status->isActive() )
                                    <option class="py-1" selected value="{{ $lead->status->id }}">{{ $lead->status->name }}</option>
                                @endif

                                @foreach($statuses as $status)
                                    <option class="py-1"
                                            @if( ( isset($lead) && $status->id == $lead->status_id ) || ( !isset($lead) && $loop->first ) ) selected
                                            @endif
                                            value="{{ $status->id }}">{{ $status->name }}</option>
                                @endforeach
                            </select>
                            <x-jet-input-error for="status_id" class="mt-2"/>
                        </div>
                        <div class="flex-1">
                            <x-jet-label for="source" value="{{__('leads.source')}}"/>
                            <select class="w-full bg-white rounded px-3 py-2" required id="source" name="source_id">
                                @foreach($sources as $source)
                                    <option class="py-1"
                                            @if( ( isset($lead) && $source->id == $lead->source_id ) || $loop->first ) selected
                                            @endif
                                            value="{{ $source->id }}">{{ $source->name }}</option>
                                @endforeach
                            </select>
                            <x-jet-input-error for="source" class="mt-2"/>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 gap-4 mt-2">
                        <div>
                            <x-jet-label value="{{__('Contractors')}}"/>
                            @if( isset($lead) )
                                @forelse($lead->contractors as $contractor)
                                    <div class="bg-gray-200 rounded-lg p-2 text-sm">
                                        <div
                                            class="flex flex-col md:flex-row space-y-2 md:space-y-0 md:space-x-2 justify-between md:items-center">
                                            <div>{{ $contractor->title }}</div>
                                            <div>
                                                <div class="flex space-x-1">
                                                    <a href="tel:{{ $contractor->phone }}">{{ $contractor->getFormattedPhone() }}</a>
                                                    <a href="https://wa.me/{{ $contractor->phone }}" target="_blank">
                                                        <x-tabler-brand-whatsapp class="cursor-pointer text-green-400"/>
                                                    </a>
                                                </div>
                                                <a href="mailto:{{ $contractor->email }}">{{ $contractor->email }}</a>
                                            </div>

                                            <div
                                                class="flex flex-row md:flex-col justify-self-end md:items-center space-x-1 space-y-1">

                                                <x-tabler-edit class="cursor-pointer"
                                                               wire:click="startEditContractor({{$contractor->id}})"/>
                                                <x-tabler-backspace class="cursor-pointer"
                                                                    wire:click="deleteContractorFromLead({{$contractor->id}})"/>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="bg-gray-200 rounded-lg p-2 text-sm">Не указан</div>
                                @endforelse
                            @endif

                            @foreach($contractors as $contractor)
                                <div class="bg-gray-200 rounded-lg p-2 text-sm">
                                    <div class="grid grid-cols-4 gap-2">
                                        <div>{{ $contractor['title'] }}</div>
                                        <div>
                                            <a href="tel:{{ $contractor['phone'] }}">{{ $contractor['phone'] }}</a>
                                        </div>
                                        <div>
                                            <a href="mailto:{{ $contractor['email'] }}">{{ $contractor['email'] }}</a>
                                        </div>
                                        <div class="justify-self-end">
                                            {{-- <x-tabler-edit class="cursor-pointer"
                                                            wire:click="startEditContractor({{$contractor['id']}})"/> --}}
                                        </div>
                                    </div>
                                </div>
                            @endforeach

                        </div>
                        <div class="justify-self-end">
                            <x-jet-button type="button" wire:click="chooseContractor()">{{__('Add contractor')}}</x-jet-button>
                        </div>
                    </div>

                    @if( isset($lead) )
                        <div class="grid grid-cols-1 gap-4 mt-2">
                            <div>
                                <x-jet-label value="Оплаты"/>
                                @foreach($lead->billings as $billing)
                                    <div class="bg-gray-200 rounded-lg p-2 mt-1 text-sm">
                                        <div class="grid grid-cols-4 gap-2">
                                            <div>{{ $billing->type }}</div>
                                            <div>{{ number_format($billing->total, 0,'', ' ') }}</div>
                                            <div>{{ $billing->made_at->setTimezone( Auth::user()->timezone )->format("d.m.Y H:i") }}</div>
                                            {{--                            </div>--}}
                                            <div class="justify-self-end space-x-1- space-y-1">
                                                {{--                                <x-jet-button wire:click="$emit('startEditBilling', {{ $billing->id }})" type="button">Редактировать</x-jet-button>--}}
                                                {{--                                <x-jet-danger-button wire:click="$emit('deleteBilling', {{ $billing->id }})" type="button">Удалить</x-jet-danger-button>--}}
                                                <x-tabler-edit class="cursor-pointer"
                                                               wire:click="$emit('startEditBilling', {{ $billing->id }})"/>
                                                <x-tabler-backspace class="cursor-pointer"
                                                                    wire:click="$emit('deleteBilling', {{ $billing->id }})"/>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                                <div class="text-sm mt-1">
                                    Итого: {{ number_format( $lead->billings->sum('total'), 0, '', ' ') }} </div>
                            </div>
                            <div class="justify-self-end">
                                <x-jet-button type="button" wire:click="$emit('makePayment', {{ $lead->id }})"> Создать оплату
                                </x-jet-button>
                            </div>
                        </div>
                    @endif

                    <div class="col-span-6 sm:col-span-4 mb-2">
                        <x-jet-label value="{{__('Responsible person')}}"/>
                        <livewire:responsible-user-search
                            name="responsible_id"
                            placeholder="{{__('integrations.search_responsible_person')}}"
                            :searchable="true"
                            :value="isset($lead) ? $lead->responsible->id : Auth::id()"
                        />
                        <x-jet-input-error for="responsible_id" class="mt-2"/>
                    </div>

                    <x-jet-button>{{__('leads.save_lead')}}</x-jet-button>
                </div>

                <div class="w-full md:flex-1 md:max-w-md">
                    <div class="col-span-6 sm:col-span-4">
                        <x-jet-label value="{{ __("leads.description") }}"/>
                        <div wire:ignore>
                            @if( isset($lead) )
                                @trix($lead, 'description')

                            @else
                                @trix(\App\Models\Lead::class, 'description')
                            @endif
                        </div>
                        <x-jet-input-error for="description" class="mt-2"/>
                    </div>

                    @if( Auth::user()->isAdmin() )
                        <div class="col-span-6 sm:col-span-4 mt-2">
                            <x-jet-label value="{{ __('Team') }}"/>
                            @if( isset($lead) )
                                <livewire:team-search
                                    name="team_id"
                                    placeholder="{{__('integrations.search_team')}}"
                                    :searchable="true"
                                    :value="$lead->team_id"
                                />
                            @else
                                <livewire:team-search
                                    name="team_id"
                                    placeholder="{{__('integrations.search_team')}}"
                                    :searchable="true"
                                    :value="Auth::user()->currentTeam->id"
                                />
                            @endif
                            <x-jet-input-error for="team_id" class="mt-2"/>
                        </div>
                    @endif
                </div>

            </div>
            </form>
            <div class="col-span-6 sm:col-span-4 mt-2">
                <x-jet-label value="Комментарии"/>
                <div class="divide-y space-y-2">
                    @isset($lead)
                        @foreach($lead->comments as $comment)
                            <div class="flex pt-2">
                                <div class="img w-1/6 text-center">
                                    <img class="w-5/6" src="{{ $comment->user->profile_photo_url }}"
                                         alt="">
                                </div>
                                <div> {!! $comment->getContent() !!} </div>
                                @if( $comment->isAuthor() )
                                    <div>
                                        <x-tabler-backspace class="cursor-pointer"
                                                            wire:click="deleteComment({{$comment->id}})"/>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    @endisset
                </div>

                <form
                    wire:submit.prevent="addComment(Object.fromEntries(new FormData($event.target)))"
                    class="pb-2">
                    <div wire:ignore class="my-2 addComment">
                        @trix(\App\Models\Comment::class, 'description')
                    </div>
                    <x-jet-button>Добавить</x-jet-button>
                </form>
            </div>
        </div>
        <div role="tabpanel" class="@if($this->activeTab === 'products') active @endif" id="products">
            <div class="space-y-2">
                <div class="grid gap-3 grid-cols-12 text-center py-5 bg-gray-100 rounded-lg">
                    <div>
                        <input type="checkbox">
                    </div>
                    <div class="col-start-2 col-end-5">
                        Наименование и характеристика
                    </div>
                    <div>
                        Кол-во
                    </div>
                    <div>
                        Цена
                    </div>
                    <div>
                        Скидка
                    </div>
                    <div>
                        Сумма
                    </div>
                    <div>
                        % НДС
                    </div>
                    <div>
                        Сумма НДС
                    </div>
                    <div>
                        Всего
                    </div>
                </div>
                @foreach($products as $productKey => $product)
                    <div class="grid grid-cols-12 gap-5 text-center py-5 bg-gray-100 rounded-lg">
                        <div>
                            <input type="checkbox">
                        </div>
                        <div class="col-start-2 col-end-5">
                            <x-vendor.nomenclature-searcher class="w-full " :value="$product['name']" outputEvent="setNomenclatureSearcher" outputParams="{productKey: {{$productKey}}}" wire:model="products.{{ $productKey }}.name">
                            </x-vendor.nomenclature-searcher>
                        </div>
                        <div>
                            <x-jet-input class="w-full p-1 text-center" wire:model.lazy="products.{{ $productKey }}.quantity">
                            </x-jet-input>
                        </div>
                        <div>
                            <x-jet-input class="w-full p-1 text-center"  wire:model.lazy="products.{{ $productKey }}.price">
                            </x-jet-input>
                        </div>
                        <div>
                            <x-jet-input class="w-full p-1 text-center" wire:model.lazy="products.{{ $productKey }}.discount">
                            </x-jet-input>
                        </div>
                        <div>
                            <x-jet-input class="w-full p-1 text-center" wire:model="products.{{ $productKey }}.sum" readonly>
                            </x-jet-input>
                        </div>
                        <div>
                            <x-jet-input class="w-full p-1 text-center" wire:model.lazy="products.{{ $productKey }}.vat_rate">
                            </x-jet-input>
                        </div>
                        <div>
                            <x-jet-input class="w-full p-1 text-center" wire:model="products.{{ $productKey }}.sum_vat_rate" readonly>
                            </x-jet-input>
                        </div>
                        <div>
                            <x-jet-input class="w-full p-1 text-center"  wire:model="products.{{ $productKey }}.total" readonly>
                            </x-jet-input>
                        </div>
                        <div class="p-1">
                            <span>
                                <svg class="float-left" xmlns="http://www.w3.org/2000/svg" width="18" height="20" viewBox="0 0 18 20" fill="none">
                                    <path d="M5 5V13C5 13.5304 5.21071 14.0391 5.58579 14.4142C5.96086 14.7893 6.46957 15 7 15H13M5 5V3C5 2.46957 5.21071 1.96086 5.58579 1.58579C5.96086 1.21071 6.46957 1 7 1H11.586C11.8512 1.00006 12.1055 1.10545 12.293 1.293L16.707 5.707C16.8946 5.89449 16.9999 6.1488 17 6.414V13C17 13.5304 16.7893 14.0391 16.4142 14.4142C16.0391 14.7893 15.5304 15 15 15H13M5 5H3C2.46957 5 1.96086 5.21071 1.58579 5.58579C1.21071 5.96086 1 6.46957 1 7V17C1 17.5304 1.21071 18.0391 1.58579 18.4142C1.96086 18.7893 2.46957 19 3 19H11C11.5304 19 12.0391 18.7893 12.4142 18.4142C12.7893 18.0391 13 17.5304 13 17V15" stroke="#252F3F" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </span>
                            <span class="cursor-pointer text-gray-600 font-bold" wire:click="deleteProduct({{$productKey}})">
                                X
                            </span>
                        </div>
                    </div>
                @endforeach
                <div class="grid grid-cols-4">
                    <div>
                        <div class="grid grid-cols-2  items-center">
                            <div>
                                <span>НДС:</span>
                            </div>
                            <div>
                                <x-jet-input class="text-center w-full p-1 border" readonly :value="collect($products)->sum('sum_vat_rate')">
                                </x-jet-input>
                            </div>
                        </div>
                        <div class="mt-2 grid grid-cols-2 items-center">
                            <div>
                                <span>Всего:</span>
                            </div>
                            <div>
                                <x-jet-input  class="text-center w-full p-2 border" readonly  :value="collect($products)->sum('total')">
                                </x-jet-input>
                            </div>
                        </div>
                    </div>
                    <div class="col-start-2 col-end-5 text-right">
                        <x-jet-button wire:click="addEmptyProduct" class="mt-3">
                            Добавить строку
                        </x-jet-button> <br>
                        <x-jet-button wire:click="saveProducts" class="mt-3">
                            Сохранить
                        </x-jet-button> <br>
                        <x-jet-button wire:click="printProducts" class="mt-3">
                            Печать
                        </x-jet-button>
                    </div>
                </div>

            </div>
        </div>
        <div role="tabpanel" class="@if($this->activeTab === 'calls') active @endif" id="calls">
            @isset($lead->contractors)
                @if( $lead->contractors->count() > 0 )

                    <?php $phoneCalls = $lead->contractors[0]->phoneCalls()->orderByDesc('id')->get(); ?>

                    @if($phoneCalls->count() > 0)
                        <div class="w-full md:flex-1">
                            <div class="col-span-6 sm:col-span-4">
                                <x-jet-label value="{{__('Calls')}}"/>
                                <div class="flex flex-col">
                                    @foreach( $phoneCalls as $phoneCall)
                                        <x-audio-telephony :model="$phoneCall"/>
                                    @endforeach

                                    {{-- $phoneCalls->links() --}}
                                </div>
                            </div>
                        </div>
                    @endif

                    <?php $emails = $lead->contractors[0]->emails()->orderByDesc('id')->get(); ?>

                    @if($emails->count() > 0)
                        <div class="w-full md:flex-1">
                            <div class="col-span-6 sm:col-span-4">
                                <x-jet-label value="E-mail"/>
                                <div class="flex flex-col">
                                    @foreach( $emails as $email)
                                        <div class="border-gray-300 mb-2 border-b">
                                            <div class="flex justify-between items-start">
                                        <span class="text-sm">
                                            @if($email->type == 'Incoming')
                                                Входящий
                                            @elseif($email->type == 'Outcoming')
                                                Исходящий
                                            @endif
                                        </span>
                                                <span class="text-xs">
                                            @displayDate($email->made_at, 'd.m.Y H:i')
                                        </span>
                                            </div>
                                            <a target="_blank" href="{{ route('email', $email->id) }}"> {{ $email->title }} </a>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif
                @endif
            @endisset
        </div>
    </div>
</div>



