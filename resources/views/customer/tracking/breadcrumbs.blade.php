<ul
    class="bg-white shadow-[0_2px_8px_-1px_rgba(6,81,237,0.4)] p-2 space-x-4 w-max flex items-center rounded-lg mx-auto font-[sans-serif] mt-4">
    <li
        class="px-4 py-2.5 text-sm font-bold cursor-pointer flex items-center
        hover:text-orange-900
          {{ Route::currentRouteName() == 'customer.tracking.all' ? 'border-b-2 border-orange-900 text-orange-900' : 'text-gray-400 ' }}">
        <a onclick="window.location.href = '{{ route('customer.tracking.all') }}'">All</a>
    </li>
    
    <li
        class="px-4 py-2.5 text-sm font-bold cursor-pointer flex items-center
        hover:text-orange-900
          {{ Route::currentRouteName() == 'customer.tracking.pending' ? 'border-b-2 border-orange-900 text-orange-900' : 'text-gray-400 ' }}">
        <a onclick="window.location.href = '{{ route('customer.tracking.pending') }}'">Pending</a>
    </li>

    <li
        class="px-4 py-2.5 text-sm font-bold cursor-pointer flex items-center
        hover:text-orange-900
          {{ Route::currentRouteName() == 'customer.tracking.processed' ? 'border-b-2 border-orange-900 text-orange-900' : 'text-gray-400 ' }}">
        <a onclick="window.location.href = '{{ route('customer.tracking.processed') }}'">Processed</a>
    </li>

    <li
        class="px-4 py-2.5 text-sm font-bold cursor-pointer flex items-center
        hover:text-orange-900
          {{ Route::currentRouteName() == 'customer.tracking.receiving' ? 'border-b-2 border-orange-900 text-orange-900' : 'text-gray-400 ' }}">
        <a onclick="window.location.href = '{{ route('customer.tracking.receiving') }}'">To Receive</a>
    </li>

    <li
        class="px-4 py-2.5 text-sm font-bold cursor-pointer flex items-center
        hover:text-orange-900
          {{ Route::currentRouteName() == 'customer.tracking.delivered' ? 'border-b-2 border-orange-900 text-orange-900' : 'text-gray-400 ' }}">
        <a onclick="window.location.href = '{{ route('customer.tracking.delivered') }}'">Completed</a>
    </li>

    <li
        class="px-4 py-2.5 text-sm font-bold cursor-pointer flex items-center
        hover:text-orange-900
          {{ Route::currentRouteName() == 'customer.tracking.cancelled' ? 'border-b-2 border-orange-900 text-orange-900' : 'text-gray-400 ' }}">
        <a onclick="window.location.href = '{{ route('customer.tracking.cancelled') }}'">Cancelled</a>
    </li>
</ul>
