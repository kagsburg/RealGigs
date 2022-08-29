<x-layout>


@include('partials._hero')
@include('partials._search')
<div class="lg:grid lg:grid-cols-2 gap-4 space-y-4 md:space-y-0 mx-4">

@unless (count($collection)==0)
    

@foreach ($collection as $item)
 <x-listings-card :item="$item" />

    
@endforeach
@else 
<p>No Listings FOund</p> 
@endunless
</div>
</x-layout>