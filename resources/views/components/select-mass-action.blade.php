<select wire:model="massAction" wire:change="chooseMassAction()" class="select select max-w-xs bg-gray-200 ml-1">
    <option value="-1" selected="selected">{{__('Select an action')}}</option>
    <option value="3">{{__('Read it')}}</option>
    <option value="1">{{__('Change status')}}</option>
    <option value="2">{{__('Delete')}}</option>
</select>
