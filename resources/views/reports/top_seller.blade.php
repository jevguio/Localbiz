<x-app-layout>
    <div class="mb-4">
        <label for="monthpicker" class="block text-sm font-medium text-gray-700">Select Month & Year:</label>
        <input type="month" id="monthpicker" name="monthpicker"
            class="mt-1 p-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 w-64"  max="{{ now()->format('Y-m') }}">
    </div>

    @include('reports.top_seller_component')

    @if($isViewBTN)
        <a href="{{ route('owner.topseller.export') }}" id="export_topseller"
            class="btn btn-primary bg-blue-700 hover:bg-blue-800 text-white px-4 py-2 rounded-md"
            style="position:absolute;right:21%; bottom:23%">Download PDF</a>
    @endif
</x-app-layout>
