<x-app-layout>
    <div class="p-4 sm:ml-64  pb-5">
        <form action="{{ route('owner.topseller.export') }}">
            <div class="mb-4 mt-4 ">
                <a href="{{ route('owner.reports') }}" class="bg-info p-2 mb-4 pt-4" style="border-radius: 10px;">
                    <i class="bx bx-chevron-left text-white" style="font-size:x-large"></i>
                </a>
                <label for="monthpicker" class="block text-sm font-medium text-gray-700 mt-3">Select Month & Year:</label>
                <input type="month" id="monthpicker" name="monthpicker" required
                    class="mt-1 p-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 w-64"
                    max="{{ now()->format('Y-m') }}">
            </div>
            <div class="mb-4 mt-4 ">
                @include('reports.top_seller_component')
            </div>
            @if ($isViewBTN)
                <div class="p-5 relative">
                    <button type="submit" id="export_topseller"
                        class="btn btn-primary bg-blue-700 hover:bg-blue-800 text-white px-4 py-2 mb-5 rounded-md absolute right-4 -bottom-5">Download
                        PDF</a>
                </div>
            @endif
        </form>
    </div>
</x-app-layout>
