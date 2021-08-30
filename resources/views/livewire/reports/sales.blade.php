<div>
    <div class="flex flex-col md:flex-row space-x-0 md:space-x-2 space-y-2 md:space-y-0 items-center">

        <x-jet-input class="ml-1 datepicker inline-block mb-1" type="text" placeholder="{{__('Date from')}}"
                     value="{{ $date_start }}" wire:change="searchByDateStart($event.target.value)"/>
        <x-jet-input class="ml-1 datepicker inline-block mb-1" type="text" placeholder="{{__('Date to')}}" value="{{ $date_end }}"
                     wire:change="searchByDateEnd($event.target.value)"/>

    </div>

    <div class="mt-2">
        <div class="space-y-2">
            @forelse($data as $item)
                <div>
                    <div class="px-2 py-4 md:px-4 md:py-4 bg-white overflow-hidden shadow-sm sm:rounded-lg flex items-start md:items-center">
                        <div class="w-full flex flex-col md:flex-row md:justify-between md:items-center space-x-0 md:space-x-2 space-y-2 md:space-y-0">
                            <div class="w-full md:w-1/6">
                                <?php $date = \Carbon\Carbon::parse($item->date); ?>
                                <p>{{__('Date')}}: @displayDate($date, 'd.m.Y') </p>
                            </div>

                            <div class="w-full md:w-1/6">
                                <p>{{__('reports.quantity_sales')}}: {{ $item->count }}</p>
                            </div>

                            <div class="w-full md:w-1/6">
                                <p>{{__('reports.sum_payments')}}: {{ \App\Helpers\Sales::getSummaryByDay( $item->date ) }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <p>{{__('No data available')}}</p>
            @endforelse
        </div>
    </div>

    <div class="mt-2">
        <div class="flex space-x-2">
            <p class="font-bold">{{__('reports.total')}}</p>
            <div>{{__('reports.quantity_sales')}}: {{ $count }} </div>
            <div>{{__('reports.sum_payments')}}: {{ $summary }} </div>

        </div>
    </div>

    <div class="mt-2">{{ $data->links()  }}</div>

</div>
