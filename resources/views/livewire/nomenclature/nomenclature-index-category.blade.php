<div>
    <div
        :active="$category['id'] === $activeCategory"
        href="javascript:void(0);"
        class="cursor-pointer"
    >
        <div class="flex items-center">
            <div class="inline-flex flex-1">
                @if($hasChild)
                    @if($childrenOpen)
                        <a href="javascript:void(0);" class="pr-1 inline-flex items-center pt-1 text-sm font-medium leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out" wire:click="openChild">-</a>
                    @else
                        <a href="javascript:void(0);" class="pr-1 inline-flex items-center pt-1 text-sm font-medium leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out" wire:click="openChild">+</a>
                    @endif
                @else
                    <span class="pr-1" style="opacity: 0">+</span>
                @endif
                <span
                    wire:click="setActiveCategory"
                    class="inline-flex items-center pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out {{$category['id'] === $activeCategory ? 'text-gray-900 border-indigo-400 focus:border-indigo-700' : ''}}"
                >
                {{$category['name']}}
            </span>
            </div>
            <div class="inline-flex">
                <span class="float-right pr-2">
                <a wire:click="$emit('openCategoryEditModal', {{$category['id']}});">
                    <svg width="20" height="20" viewBox="0 0 15 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M5.92634 2.16113H2.09474C1.8044 2.16113 1.52595 2.27647 1.32064 2.48178C1.11534 2.68708 1 2.96553 1 3.25587V10.9191C1 11.2094 1.11534 11.4879 1.32064 11.6932C1.52595 11.8985 1.8044 12.0138 2.09474 12.0138H9.75794C10.0483 12.0138 10.3267 11.8985 10.532 11.6932C10.7373 11.4879 10.8527 11.2094 10.8527 10.9191V7.08747" stroke="#D2D6DC" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M10.0316 1.34009C10.2493 1.12234 10.5447 1 10.8526 1C11.1606 1 11.4559 1.12234 11.6737 1.34009C11.8914 1.55785 12.0138 1.85319 12.0138 2.16115C12.0138 2.46911 11.8914 2.76445 11.6737 2.98221L6.47366 8.18223L4.28418 8.7296L4.83155 6.54012L10.0316 1.34009Z" stroke="#D2D6DC" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </a>
                </span>
                <span class="float-right ">
                    <form action="{{route('nomenclatures.category.destroy', $category)}}" method="POST">
                        @method('DELETE')
                        @csrf
                        <button type="submit">
                            <svg width="17" height="17" viewBox="0 0 10 11" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M5.00006 3.78899e-09C5.44879 -2.95217e-05 5.88052 0.172499 6.20669 0.482192C6.53286 0.791885 6.72873 1.21527 6.75414 1.66548L6.75684 1.76543H9.59471C9.69743 1.76546 9.79631 1.80468 9.87136 1.87515C9.94642 1.94562 9.99206 2.04209 9.99906 2.14507C10.0061 2.24805 9.9739 2.34987 9.90908 2.42994C9.84426 2.51001 9.75161 2.56237 9.64985 2.57644L9.59471 2.58025H9.16444L8.47254 9.65284C8.43787 10.0054 8.27962 10.3342 8.02604 10.5804C7.77245 10.8265 7.44006 10.9741 7.0882 10.9967L6.99306 11H3.00706C2.65434 11 2.31311 10.8739 2.04435 10.6444C1.7756 10.4148 1.59684 10.0967 1.54002 9.74681L1.52759 9.6523L0.835146 2.58025H0.40541C0.307443 2.58024 0.212791 2.54459 0.138958 2.47988C0.0651255 2.41517 0.0171074 2.32578 0.00378381 2.22825L0 2.17284C4.13135e-06 2.07439 0.0354829 1.97927 0.0998753 1.90507C0.164268 1.83088 0.253217 1.78262 0.350275 1.76923L0.40541 1.76543H3.24328C3.24328 1.29721 3.42837 0.848166 3.75783 0.517083C4.08729 0.186 4.53414 3.78899e-09 5.00006 3.78899e-09ZM8.34983 2.58025H1.64975L2.33462 9.57244C2.34979 9.72846 2.4182 9.8744 2.52826 9.98549C2.63832 10.0966 2.78323 10.166 2.93841 10.1819L3.00706 10.1852H6.99306C7.31739 10.1852 7.59253 9.95432 7.65523 9.64089L7.66604 9.57244L8.34929 2.58025H8.34983ZM5.94602 4.07407C6.04399 4.07408 6.13864 4.10973 6.21247 4.17444C6.2863 4.23915 6.33432 4.32854 6.34765 4.42607L6.35143 4.48148V8.28395C6.3514 8.38717 6.31238 8.48654 6.24225 8.56196C6.17213 8.63739 6.07613 8.68325 5.97365 8.69029C5.87117 8.69732 5.76986 8.665 5.69018 8.59986C5.6105 8.53472 5.55839 8.44162 5.54439 8.33936L5.54061 8.28395V4.48148C5.54061 4.37343 5.58332 4.2698 5.65935 4.1934C5.73538 4.117 5.8385 4.07407 5.94602 4.07407ZM4.0541 4.07407C4.15207 4.07408 4.24672 4.10973 4.32056 4.17444C4.39439 4.23915 4.44241 4.32854 4.45573 4.42607L4.45951 4.48148V8.28395C4.45948 8.38717 4.42046 8.48654 4.35034 8.56196C4.28021 8.63739 4.18422 8.68325 4.08174 8.69029C3.97926 8.69732 3.87794 8.665 3.79826 8.59986C3.71858 8.53472 3.66648 8.44162 3.65248 8.33936L3.64869 8.28395V4.48148C3.64869 4.37343 3.69141 4.2698 3.76744 4.1934C3.84347 4.117 3.94658 4.07407 4.0541 4.07407ZM5.00006 0.814815C4.76266 0.814824 4.53394 0.904537 4.3593 1.06615C4.18467 1.22776 4.07688 1.44945 4.05735 1.68721L4.0541 1.76543H5.94602C5.94602 1.51331 5.84636 1.27152 5.66896 1.09324C5.49155 0.914969 5.25095 0.814815 5.00006 0.814815Z" fill="#D2D6DC"/>
                        </svg>
                        </button>
                    </form>
                </span>

            </div>
        </div>



        @if($childrenOpen)
            <div class="pl-2">
                @foreach($children as $item)
                    <div wire:ignore>
                        @livewire('nomenclature.nomenclature-index-category', ['category' => $item, 'activeCategory' => $activeCategory])
                    </div>
                @endforeach
            </div>
        @endif
    </div>

</div>
