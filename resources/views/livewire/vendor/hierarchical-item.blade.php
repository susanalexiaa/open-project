<div class="pl-3 pt-1 pb-1">
    <div>
        @if($hasChild)
            @if($childrenOpen)
                <a href="javascript:void(0);" class="p-2" wire:click="getChild">-</a>
            @else
                <a href="javascript:void(0);" class="p-2" wire:click="getChild">+</a>
            @endif
        @else
            <span class="p-2" style="opacity: 0">+</span>
        @endif

        <span>
            <a href="javascript:void(0);" onclick="localStorage.setItem('{{$storageName}}', JSON.stringify({
                'name': '{{$item->$elementName}}',
                'id': {{$item->id}}
                })); return window.close();">
                {{$item->$elementName}}
            </a>
        </span>
    </div>
    @if($childrenOpen)
        <div>
            @foreach($children as $item)
                @livewire('vendor.hierarchical-item', ['item' => $item, 'storageName' => $storageName, 'elementName' => $elementName])
            @endforeach
        </div>
    @endif

</div>
