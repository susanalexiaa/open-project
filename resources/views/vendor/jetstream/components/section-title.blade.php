<div class="md:col-span-1">
    <div class="px-4 sm:px-0">
        <h3 class="text-lg font-medium text-gray-900">{{ $title }}</h3>

        <p class="mt-1 text-sm text-gray-600">
            {{ $description }}
        </p>
        @isset($actions)
            <p class="mt-4 text-sm">
                {{ $actions }}
            </p>
        @endisset
    </div>
</div>
