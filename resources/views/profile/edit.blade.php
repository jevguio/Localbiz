<x-app-layout>
    <div class="p-4 sm:ml-64 " style="background-color:rgba(68, 68, 68, 0.13) ">
        <div class="p-4 border-2 border-gray-200 border-dashed rounded-lg dark:border-gray-700 ">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                
                <!-- Forms will always be in one column with spacing -->
                <div class="flex flex-col space-y-6">
                    
                    <!-- Full width for better responsiveness -->
                    <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg w-full">
                        <div class="max-w-xl">
                            @include('profile.partials.update-profile-information-form')
                        </div>
                    </div>

                    <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg w-full">
                        <div class="max-w-xl">
                            @include('profile.partials.update-password-form')
                        </div>
                    </div>

                </div>
                
            </div>
        </div>
    </div>
</x-app-layout>
