<ul class="bg-white shadow-[0_2px_8px_-1px_rgba(6,81,237,0.4)] p-2 space-x-4 w-max flex items-center rounded-lg mx-auto font-[sans-serif] mt-4">
 
    <li class="px-4 py-2.5 rounded-lg text-sm font-bold cursor-pointer flex items-center
        text-gray-400 hover:bg-gray-100 
        {{ Route::currentRouteName() == 'seller.tracking.processed' ? 'border-b-2 border-blue-500 text-blue-600' : '' }}">
        <a href="{{ route('seller.tracking.processed') }}">Processing</a>
    </li>
    <li class="px-4 py-2.5 rounded-lg text-sm font-bold cursor-pointer flex items-center
        text-gray-400 hover:bg-gray-100 
        {{ Route::currentRouteName() == 'seller.tracking.receiving' ? 'border-b-2 border-blue-500 text-blue-600' : '' }}">
        <a href="{{ route('seller.tracking.receiving') }}">To Receive</a>
    </li>
    <li class="px-4 py-2.5 rounded-lg text-sm font-bold cursor-pointer flex items-center
        text-gray-400 hover:bg-gray-100 
        {{ Route::currentRouteName() == 'seller.tracking.delivered' ? 'border-b-2 border-blue-500 text-blue-600' : '' }}">
        <a href="{{ route('seller.tracking.delivered') }}">Completed</a>
    </li>
    <li class="px-4 py-2.5 rounded-lg text-sm font-bold cursor-pointer flex items-center
        text-gray-400 hover:bg-gray-100 
        {{ Route::currentRouteName() == 'seller.tracking.cancelled' ? 'border-b-2 border-blue-500 text-blue-600' : '' }}">
        <a href="{{ route('seller.tracking.cancelled') }}">Cancelled</a>
    </li>
</ul>
