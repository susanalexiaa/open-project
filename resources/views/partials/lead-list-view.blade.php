<?php
$related = $lead->getRelatedContractorsLeads();

$total = $related->count();

$isViewByFolder = $lead->contractor_id && $total > 1;
?>

<div>
    <div @if( $isViewByFolder ) wire:click="toggleRelatedView( {{$lead->id}} )"
         @else wire:click="openModalFormVisible( {{$lead->id}} )"
         @endif
         class="px-2 py-4 md:px-4 md:py-4 bg-white overflow-hidden shadow-sm sm:rounded-lg flex items-start md:items-center @if( !$lead->isReadByCurrentUser() ) bg-yellow-100 @endif">
        <div class="w-1/12 md:w-min mr-2" wire:click.stop="">
            <x-jet-checkbox wire:click="toggleCheckedByFolder({{ $lead->id }})"
                            wire:model="checked_ids.{{ $lead->id }}"/>
        </div>
        <div class="flex w-full flex-col md:flex-row md:justify-between md:items-center space-x-0 md:space-x-2 space-y-2 md:space-y-0">
            <div class="w-full md:w-1/6">
                <p>@if($lead->is_hot) 🔥 @endif Заявка № {{ $lead->id }} от @displayDate($lead->created_at, 'd.m.Y H:i')</p>
            </div>

            @if( $isViewByFolder )
                <div>
                    <div
                        class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm">
                        <p class="mr-1">{{ $total }}</p>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" width="20px"
                             stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </div>
                </div>
            @endif

            <div class="w-full md:w-1/6">
                <?php $contractors = $lead->contractors; ?>
                @if( count($contractors) > 0 ) {{ $contractors[0]->title }} @endif
            </div>

            <div class="w-full md:w-1/6">
                @if( count($contractors) > 0 ) {{ $contractors[0]->getFormattedPhone() }} @endif
            </div>

            <div class="max-w-xl">
                {{ $lead->getTextDescription(135) }}
            </div>

            <div class="w-full md:w-2/12">
                <div class="flex items-center mb-2">
                    <div class="justify-self-center">
                        <div class="w-8 h-8 rounded-full bg-{{ $lead->status->color_class }}"></div>
                    </div>
                    <div class="ml-2">{{ $lead->status->name }}</div>
                </div>
            </div>

            <div class="w-full md:w-1/12">
                {{ $lead->getPeriodOfCurrentStatus() }}
            </div>
        </div>
    </div>

    @if( $isViewByFolder )
        <div @if( !in_array($lead->id, $openCloseViewLeads) ) class="hidden" @endif>
            @foreach( $related as $lead_related)
                <div wire:click="openModalFormVisible( {{$lead_related->id}} )"
                     class="ml-10 mt-3 px-2 py-4 md:px-4 md:py-4 bg-white overflow-hidden shadow-sm sm:rounded-lg flex items-start md:items-center @if( !$lead_related->isReadByCurrentUser() ) bg-yellow-100 @endif">

                    <div class="w-1/12 md:w-min mr-2" wire:click.stop="">
                        <x-jet-checkbox wire:model="checked_ids.{{ $lead_related->id }}"/>
                    </div>

                    <div class="flex w-full flex-col md:flex-row md:justify-between md:items-center space-x-0 md:space-x-2 space-y-2 md:space-y-0">
                        <div class="w-full md:w-1/6">
                            <p>@if($lead_related->is_hot) 🔥 @endif Заявка № {{ $lead_related->id }} от @displayDate($lead_related->created_at, 'd.m.Y H:i')</p>
                        </div>

                        <?php $contractors = $lead_related->contractors; ?>
                        <div class="w-full md:w-1/6">
                            @if( count($contractors) > 0 ) {{ $contractors[0]->title }} @endif
                        </div>

                        <div class="w-full md:w-1/6">
                            @if( count($contractors) > 0 ) {{ $contractors[0]->getFormattedPhone() }} @endif
                        </div>

                        <div class="max-w-xl">
                            {{ $lead_related->getTextDescription(135) }}
                        </div>

                        <div class="w-full md:w-2/12">
                            <div class="flex items-center mb-2">
                                <div class="justify-self-center">
                                    <div class="w-8 h-8 rounded-full bg-{{ $lead_related->status->color_class }}"></div>
                                </div>
                                <div class="ml-2">{{ $lead_related->status->name }}</div>
                            </div>
                        </div>

                        <div class="w-full md:w-1/12">
                            {{ $lead_related->getPeriodOfCurrentStatus() }}
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
