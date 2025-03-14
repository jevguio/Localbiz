<x-app-layout>
    <div class="p-4 sm:ml-64">
        <div class="p-4 border-2 border-gray-200 border-dashed rounded-lg">
            <div class="grid grid-cols-3 gap-4 mb-4">
                <h2 class="mt-3 text-xl font-bold text-gray-900 sm:text-2xl">Dashboard</h2>
            </div>
            <hr class="my-4">
            <div class="bg-white p-4 rounded-lg">
                @if (Auth::user()->role != 'Cashier')
                    <div role="alert" class="alert alert-error">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 shrink-0 stroke-current" fill="none"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>You are not authorized to access this page.</span>
                    </div>
                @else
                    @php
                        $cashier = Auth::user()->cashier;
                    @endphp
                    @if ($cashier && $cashier->is_approved == 1)
                        <div role="alert" class="alert alert-success text-white">
                            <i class='bx bx-check-circle shrink-0 stroke-current text-lg'></i>
                            <span>Your documents have been approved. You can now access the dashboard.</span>
                        </div>
                    @elseif ($cashier && $cashier->document_file && $cashier->is_approved == 0)
                        <div role="alert" class="alert alert-success text-white">
                            <i class='bx bx-check-circle shrink-0 stroke-current text-lg'></i>
                            <span>Your documents have been uploaded. Please wait for approval from the
                                Seller.</span>
                        </div>
                    @elseif ($cashier && $cashier->is_approved == 0)
                        <form action="{{ route('cashier.dashboard.upload') }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="grid grid-cols-2 gap-4">
                                <fieldset class="fieldset">
                                    <legend class="fieldset-legend">Document File</legend>
                                    <input type="file" class="file-input w-full" name="document_file"
                                        id="document_file" required />
                                </fieldset>
                            </div>
                            <hr class="my-4">
                            <button type="submit"
                                class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center">Submit</button>
                        </form>
                    @endif
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
