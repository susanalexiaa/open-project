@section('css-dependencies')
    @parent
    <script src="https://api-maps.yandex.ru/2.1/?apikey=f3ec9ee4-28db-410a-a63c-f9feb92bfc00&lang=ru_RU"
            type="text/javascript">
    </script>
@endsection
<div>
    <div class="grid grid-cols-1 mb-4">

        @if($this->success)
            <div class="bg-green-400 relative text-white py-3 px-3 rounded-lg mb-3">
                {{ $this->success }}
            </div>
        @endif

        <label class="text-gray-800 block mb-1 font-bold text-sm tracking-wide">{{__('teams.name')}}</label>
        <x-jet-input
            class="bg-gray-100 appearance-none border-2 border-gray-200 rounded-lg w-full py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-blue-500"
            type="text"
            wire:model="name"
        >
        </x-jet-input>
        <label class="text-gray-800 mt-3 block mb-1 font-bold text-sm tracking-wide">{{__('teams.city')}}</label>
        <div wire:ignore>
            <div class="selectable-container">
                <x-vendor.selectable
                    :select2Url="url('/api/localities')"
                    placeholder="{{__('teams.city')}}"
                    buttonText="Все"
                    name="locality_id"
                    inputEventName="set-city-value"
                    outputEventName="change-city-value"
                >
                    @if(!empty($this->team->locality))
                        <option value="{{$this->team->locality->id}}">{{$this->team->locality->OFFNAME}}</option>
                    @endif
                </x-vendor.selectable>
            </div>
        </div>
        <label class="text-gray-800 mt-3 block mb-1 font-bold text-sm tracking-wide">{{__('teams.address')}}</label>
        <x-jet-input
            class="bg-gray-100 appearance-none border-2 border-gray-200 rounded-lg w-full py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-blue-500"
            type="text"
            wire:model="address"
        >
        </x-jet-input>
        <label class="text-gray-800 mt-3 block mb-1 font-bold text-sm tracking-wide">{{__('teams.coords')}}</label>
        <x-jet-input
            class="bg-gray-100 appearance-none border-2 border-gray-200 rounded-lg w-full py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-blue-500"
            type="text"
            wire:model="coords"
        >
        </x-jet-input>

        <label
            class="text-gray-800 mt-3 block mb-1 font-bold text-sm tracking-wide">{{__('teams.work_starts_at')}}</label>
        <x-jet-input
            class="bg-gray-100 appearance-none border-2 border-gray-200 rounded-lg w-full py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-blue-500"
            type="text"
            wire:model="work_starts_at"
        >
        </x-jet-input>

        <label
            class="text-gray-800 mt-3 block mb-1 font-bold text-sm tracking-wide">{{__('teams.work_ends_at')}}</label>
        <x-jet-input
            class="bg-gray-100 appearance-none border-2 border-gray-200 rounded-lg w-full py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-blue-500"
            type="text"
            wire:model="work_ends_at"
        >
        </x-jet-input>


    </div>
    @if(empty($this->team->id))
        <x-button-link
            class="mt-3"
            href="{{(URL::previous() ? URL::previous() : route('admin.teams'))}}">
            {{ __('Back') }}
        </x-button-link>
    @endif
    <x-jet-button type="button"
                  class="mt-3"
                  wire:click="updateTeam">
        {{ __('Save') }}
    </x-jet-button>
    @if($this->team->id)
        @livewire('admin.teams.team-member-manager', ['team' => $this->team])
        <x-button-link
            class="mt-3"
            href="{{(URL::previous() ? URL::previous() : route('admin.teams'))}}">
            {{ __('Back') }}
        </x-button-link>
    @endif


</div>

<script>
    addEventListener("change-city-value", function (event) {
    @this.set('locality_id', event.detail.value)
    })
</script>
</div>
