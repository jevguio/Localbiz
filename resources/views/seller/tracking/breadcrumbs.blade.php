<ul class="bg-white shadow-[0_2px_8px_-1px_rgba(6,81,237,0.4)] p-2 space-x-4 w-max flex items-center rounded-lg mx-auto font-[sans-serif] mt-4">
 
    <li class="px-4 py-2.5 text-sm font-bold cursor-pointer flex items-center
         hover:text-orange-900
        {{ Route::currentRouteName() == 'seller.tracking.processed' ? 'border-b-2 border-orange-900 text-orange-900' : 'text-gray-400' }}">
        <a href="{{ route('seller.tracking.processed') }}?filter=all">Processing</a>
    </li>
    <li class="px-4 py-2.5 text-sm font-bold cursor-pointer flex items-center
        hover:text-orange-900
        {{ Route::currentRouteName() == 'seller.tracking.receiving' ? 'border-b-2 border-orange-900 text-orange-900' : 'text-gray-400' }}">
        <a href="{{ route('seller.tracking.receiving') }}?filter=all">To Receive</a>
    </li>
    <li class="px-4 py-2.5 text-sm font-bold cursor-pointer flex items-center
        hover:text-orange-900
        {{ Route::currentRouteName() == 'seller.tracking.delivered' ? 'border-b-2 border-orange-900 text-orange-900' : 'text-gray-400' }}">
        <a href="{{ route('seller.tracking.delivered') }}?filter=all">Completed</a>
    </li>
    <li class="px-4 py-2.5 text-sm font-bold cursor-pointer flex items-center
         hover:text-orange-900
        {{ Route::currentRouteName() == 'seller.tracking.cancelled' ? 'border-b-2 border-orange-900 text-orange-900' : 'text-gray-400' }}">
        <a href="{{ route('seller.tracking.cancelled') }}?filter=all">Cancelled</a>
    </li>
</ul>
