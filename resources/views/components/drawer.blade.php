@props(['isOpen', 'onClose'])

<div class="drawer_bg fixed inset-0 overflow-hidden animate__animated animate__fadeIn @if( !$isOpen ) hidden @endif">
    <div class="absolute inset-0 overflow-hidden">
        <div wire:click="{{ $onClose }}" class="absolute inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
        <section class="absolute drawer inset-y-0 right-0 pl-10 max-w-full flex animate__animated animate__fadeInRight" aria-labelledby="slide-over-heading">
            <div class="relative w-screen {{ $attributes->get('max-width') ?? 'max-w-lg' }}">
                <div class="absolute top-0 left-0 -ml-8 pt-4 pr-2 flex sm:-ml-10 sm:pr-4">
                    <button wire:click="{{ $onClose }}" class="rounded-md text-gray-300 hover:text-white focus:outline-none focus:ring-2 focus:ring-white">
                        <span class="sr-only">Close panel</span>
                        <!-- Heroicon name: outline/x -->
                        <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <div class="h-full flex flex-col py-6 bg-white shadow-xl overflow-y-scroll">
                    {{ $slot }}
                </div>
            </div>
        </section>
    </div>
</div>
