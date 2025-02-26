<ul
    class="bg-white shadow-[0_2px_8px_-1px_rgba(6,81,237,0.4)] p-2 space-x-4 w-max flex items-center rounded-lg mx-auto font-[sans-serif] mt-4">
    <li
        class="text-gray-400 hover:bg-gray-100 px-4 py-2.5 rounded-lg text-sm font-bold cursor-pointer flex items-center">
        <a href="{{ route('seller.tracking.pending') }}">Pending</a>
    </li>
    <li
        class="text-gray-400 hover:bg-gray-100 px-4 py-2.5 rounded-lg text-sm font-bold cursor-pointer flex items-center">
        <a href="{{ route('seller.tracking.processed') }}">Processing</a>
    </li>
    <li
        class="text-gray-400 hover:bg-gray-100 px-4 py-2.5 rounded-lg text-sm font-bold cursor-pointer flex items-center">
        <a href="{{ route('seller.tracking.receiving') }}">To Receive</a>
    </li>
    <li
        class="text-gray-400 hover:bg-gray-100 px-4 py-2.5 rounded-lg text-sm font-bold cursor-pointer flex items-center">
        <a href="{{ route('seller.tracking.cancelled') }}">Cancelled</a>
    </li>
    <li
        class="text-gray-400 hover:bg-gray-100 px-4 py-2.5 rounded-lg text-sm font-bold cursor-pointer flex items-center">
        <a href="{{ route('seller.tracking.delivered') }}">Completed/Delivered</a>
    </li>
</ul>
