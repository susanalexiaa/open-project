<x-jet-label value="{{ $model->type }}"/>
<div class="text-sm text-gray-700">@displayDate($model->created_at, 'd.m.Y H:i')</div>

@if($showContractor)
  <div wire:click="openContractorOnAudio({{ $model->contractor_id }})" class="text-sm text-gray-800 mb-2">
    <p class="cursor-pointer border-b-2 inline-block">{{__('Contractor')}}</p>
  </div>
@endif

@if( $model->link )
<div class="flex flex-row items-center bg-white border px-2 py-1 rounded-3xl shadow-md mb-2 mt-0">

  <button data-id="{{ $model->id }}" type="button" data-src="{{ asset('storage/' . $model->link) }}" class="flex items-center justify-center bg-indigo-600 hover:bg-indigo-800 rounded-full h-8 w-10 audio-telephony">
    <svg class="w-6 h-6 text-white audio-telephony-svg" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path>
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
    </svg>
  </button>

  <div class="flex flex-row items-center space-x-px ml-4">
    <div class="h-2 w-1 bg-gray-500 rounded-lg"></div>
    <div class="h-2 w-1 bg-gray-500 rounded-lg"></div>
    <div class="h-4 w-1 bg-gray-500 rounded-lg"></div>
    <div class="h-8 w-1 bg-gray-500 rounded-lg"></div>
    <div class="h-8 w-1 bg-gray-500 rounded-lg"></div>
    <div class="h-10 w-1 bg-gray-500 rounded-lg"></div>
    <div class="h-10 w-1 bg-gray-500 rounded-lg"></div>
    <div class="h-12 w-1 bg-gray-500 rounded-lg"></div>
    <div class="h-10 w-1 bg-gray-500 rounded-lg"></div>
    <div class="h-6 w-1 bg-gray-500 rounded-lg"></div>
    <div class="h-5 w-1 bg-gray-500 rounded-lg"></div>
    <div class="h-4 w-1 bg-gray-500 rounded-lg"></div>
    <div class="h-3 w-1 bg-gray-500 rounded-lg"></div>
    <div class="h-10 w-1 bg-gray-500 rounded-lg"></div>
    <div class="h-2 w-1 bg-gray-500 rounded-lg"></div>
    <div class="h-10 w-1 bg-gray-500 rounded-lg"></div>
    <div class="h-8 w-1 bg-gray-500 rounded-lg"></div>
    <div class="h-8 w-1 bg-gray-500 rounded-lg"></div>
    <div class="h-1 w-1 bg-gray-500 rounded-lg"></div>
    <div class="h-1 w-1 bg-gray-500 rounded-lg"></div>
  </div>

</div>
@else
  <div class="text-red-500">
    @if( in_array($model->disposition, ['busy', 'cancel', 'failed', 'no answer']) )
      Пропущенный звонок
    @else
      {{ $model->disposition }}
    @endif
  </div>
@endif
