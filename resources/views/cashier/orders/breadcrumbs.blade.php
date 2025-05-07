<ul
    class="bg-white shadow-[0_2px_8px_-1px_rgba(6,81,237,0.4)] p-2 space-x-4 w-max flex items-center rounded-lg mx-auto font-[sans-serif] mt-4">
    <li
        class="text-gray-400
{{ Route::currentRouteName() == 'cashier.orders' ? 'border-b-2 border-orange-900 text-orange-900' : 'text-gray-400' }}
        hover:text-red-900 px-4 py-2.5 text-sm font-bold cursor-pointer flex items-center">
        <a onclick="window.location.href = '{{ route('cashier.orders') }}'">Pending Orders</a>
    </li>
    <li
        class="text-gray-400
        {{ Route::currentRouteName() == 'cashier.walkin' ? 'border-b-2 border-orange-900 text-orange-900' : 'text-gray-400' }}
        hover:text-red-900 px-4 py-2.5 text-sm font-bold cursor-pointer flex items-center">
        <a onclick="window.location.href = '{{ route('cashier.walkin') }}'">Walk-in Order</a>
    </li>
    <li
        class="text-gray-400
        {{ Route::currentRouteName() == 'cashier.walkin.orders.history' ? 'border-b-2 border-orange-900 text-orange-900' : 'text-gray-400' }}
        hover:text-red-900 px-4 py-2.5 text-sm font-bold cursor-pointer flex items-center">
        <a onclick="window.location.href = '{{ route('cashier.walkin.orders.history') }}'">Walk-in Order History</a>
    </li>
</ul>
