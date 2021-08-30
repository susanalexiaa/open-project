<div>

    <div x-data="nomenclatureSearcher({
    output: {
        name: '{{ $attributes['outputEvent'] }}',
        params: {{ $attributes['outputParams'] ?? '{}' }}
    },
    product: {
        name: '{{ $attributes['value'] }}'
    }
    })" class="relative">
        <x-jet-input x-on:keyup="sendQuery(event)" class="w-full  p-1" x-model="product.name" x-on:focus="showDropdown = true;">
        </x-jet-input>
        <template x-if="showDropdown">
            <div class="absolute top-full left-0 z-10 bg-white border border-gray-200 w-full text-left">
                <div>
                    <template x-if="loading">
                        <span>Загрузка...</span>
                    </template>
                </div>
                <div>
                    <template x-for="result in results">
                        <div x-on:click="setNomenclature(result)" class="my-2 cursor-pointer px-3">
                            <span x-text="result.text">
                            </span>
                        </div>
                    </template>
                </div>
            </div>
        </template>

    </div>

    <script>
        function nomenclatureSearcher(params) {
            return {
                url: '{{url('/api/nomenclatures/products/getList')}}',
                query: null,
                xhr: null,
                results: [],
                product:{
                    name: params.product.name,
                    id: '',
                },
                params: params,
                showDropdown: false,
                loading: false,
                sendQuery: function (event) {
                    let that = this;
                    this.loading = true;
                    if(this.xhr && this.xhr.readyState !== 4){
                        this.xhr.abort();
                    }
                    this.xhr = $.ajax({
                        url: this.url,
                        data: {q: event.target.value},
                        success: function(data) {
                            that.loading = false;
                            that.results = data.results
                        }
                    });
                },
                setNomenclature(nomenclature){
                    this.product.name = nomenclature.text;
                    this.product.id = nomenclature.id;
                    console.log(this.params.output.name);
                    let params = this.params.output.params
                    params.id = nomenclature.id;
                    params.name = nomenclature.text;
                    Livewire.emit(this.params.output.name, params);
                    this.showDropdown = false;
                }
            }
        }
    </script>
</div>


