<div>
    @foreach($list as $item)
        @livewire('vendor.hierarchical-item', ['item' => $item, 'storageName' => $storageName, 'elementName' => $elementName])
    @endforeach
</div>
