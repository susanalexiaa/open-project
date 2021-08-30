@if( !is_null($rating) )
<div class="flex justify-center items-center">
    <div class="flex items-center mt-2 mb-4">
        @for ($i = 1; $i <= $rating; $i++)
            <svg class="w-4 h-4 fill-current text-yellow-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/></svg>
        @endfor

        @for ($c = $i; $c <= 5; $c++)
            <svg class="w-4 h-4 fill-current text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/></svg>
        @endfor
    </div>
</div>
@else
<div>
    {{__('No reviews')}}
</div>
@endif
