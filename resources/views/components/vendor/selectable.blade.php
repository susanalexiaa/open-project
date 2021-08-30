<div>
    @php
        $id = rand(0, 1000);
    @endphp
    <div
        x-data="selectable({
        id: {{$id}},
        name: '{{$attributes->get('name')}}',
        placeholder: '{{$attributes->get('placeholder')}}',
        links: {
            viewAll: '{{$attributes->get('viewAllUrl')}}',
            createUrl: '{{$attributes->get('createUrl')}}',
            select2Url: '{{$attributes->get('select2Url')}}'

        },
        events: {
            outputEvent: '{{$attributes->get('outputEventName')}}',
        },
    })"
        x-init="mount($dispatch)"
        {{ '@' . $attributes->get('inputEventName') }}.window="setValueFromOutside($event)"
        @if($attributes->get('paramChangeEvent'))
        {{ '@' . $attributes->get('paramChangeEvent') }}.window="changeParam($event)"
        @endif
    >
        <div class="block">
            <div class="flex">
                <x-vendor.select2
                    :url="$attributes->get('select2Url')"
                    :id="$id"
                    :name="$attributes->get('name')"
                    :placeholder="$attributes->get('placeholder')"
                    class="block flex-auto rounded-r-none"
                    :multiple="$attributes->get('multiple')"
                >
                    {{$slot}}
                </x-vendor.select2>
                <div class="flex">
                    @php
                        $class = ($attributes->get('createUrl') ? 'rounded-l-none rounded-r-none' : 'rounded-l-none')
                    @endphp
{{--                    <x-jet-button x-on:click="openList"--}}
{{--                                  :class="$class"--}}
{{--                    >--}}
{{--                        {{$attributes->get('buttonText')}}--}}
{{--                    </x-jet-button>--}}
                    @if($attributes->get('createUrl'))
                        <x-jet-button x-on:click="openCreateForm" class="rounded-l-none bg-blue-300 hover:bg-blue-400">
                            {{__("Create")}}
                        </x-jet-button>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <script>
        function selectable(parameters){
            return {
                selected: null,
                select2Id: parameters.id,
                select2: null,
                select2Container: null,
                links: {
                    viewAll: parameters.links.viewAll,
                },
                openList(){
                    var params = {
                        windowName: "Выберите город",
                        params: "menubar=false,location=false,resizable=yes,scrollbars=false,status=false",
                        paramName: parameters.name
                    }
                    let that = this;
                    windowOpener
                        .open(that.links.viewAll, params)
                        .then(function (result){
                            that.selected = result;
                            var data = {
                                id: that.selected.id,
                                text: that.selected.name
                            };
                            var newOption = new Option(data.text, data.id, false, true);
                            var select = $(`.select2-ajax[data-id="${that.select2Id}"]`)
                            select.append(newOption).trigger('change');
                        })
                },
                openCreateForm(){
                    var params = {
                        windowName: "Выберите город",
                        params: "menubar=false,location=false,resizable=yes,scrollbars=false,status=false",
                        paramName: parameters.name
                    }
                    let that = this;
                    windowOpener
                        .open(parameters.links.createUrl, params)
                        .then(function (result){
                            that.selected = result;
                            var data = {
                                id: that.selected.id,
                                text: that.selected.name
                            };
                            var newOption = new Option(data.text, data.id, true, true);
                            var select = $(`.select2-ajax[data-id="${that.select2Id}"]`)
                            select.append(newOption).trigger('change');
                        })
                },
                mount($dispatch) {
                    this.select2 = $(this.$refs.select);
                    this.select2Container = this.select2.parent();
                    this.renderSelect2(parameters.links.select2Url);
                    this.select2.on('change', (event) => {
                        // this.selectValue = this.select2.val();
                        $dispatch(parameters.events.outputEvent, {value: this.select2.val()})
                    });
                    this.$watch('selectValue', (value) => {
                        this.select2.val(value).trigger('change');
                        $dispatch(parameters.events.outputEvent, {value: this.selectValue})
                    });
                },
                setValueFromOutside(event){
                    if(Array.isArray(event.detail)){
                        let that = this;
                        event.detail.forEach(function (item){
                            that.setValueHandler(item);
                        }, this)
                    }else{
                        this.setValueHandler(event.detail);
                    }
                },
                setValueHandler(item){
                    if (item.id === 0){
                        $(this.select2).html('');
                    }else{
                        if ($(this.select2).find('option[value="' + item.id + '"]').length === 0){
                            var newOption = new Option(item.text, item.id, true, true);
                            $(this.select2).append(newOption).trigger('change');
                        }

                    }
                },
                changeParam(event){
                    if (event.detail.name === 'viewAll'){
                        this.links.viewAll = event.detail.value;
                    }
                    if (event.detail.name === 'select2Url'){
                        this.renderSelect2(event.detail.value);
                    }
                },
                renderSelect2(url){
                    this.select2.select2({
                        placeholder: parameters.placeholder,
                        ajax: {
                            url: url,
                            dataType: 'json'
                        },
                        minimumInputLength: 0,
                    });
                    this.select2Container.find('.select2').addClass('flex-auto');
                    this.select2Container.find('.select2-selection').addClass('rounded-r-none min-h-full');
                }
            }
        }
    </script>

</div>
