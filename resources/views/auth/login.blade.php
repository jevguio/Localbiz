<x-guest-layout>
    <div class="bg-gray-50 font-[sans-serif]">
        <div class="min-h-screen flex flex-col items-center justify-center py-6 px-4">
            <div class="max-w-md w-full">
                <img src="{{ asset('assets/img/logo.jpg') }}" alt="logo" class="w-60 my-2 mx-auto">
                <div class="p-8 rounded-2xl bg-white shadow">
                    <h2 class="text-gray-800 text-center text-2xl font-bold">Sign in</h2>
                    <form class="mt-8 space-y-4" method="POST" action="{{ route('login') }}">
                        @csrf
                        <div>
                            <label class="text-gray-800 text-sm mb-2 block">Email Address</label>
                            <div class="relative flex items-center">
                                <input id="email" name="email" type="email" required
                                    class="w-full text-gray-800 text-sm border border-gray-300 px-4 py-3 rounded-md outline-blue-600"
                                    placeholder="Enter email address" />
                                <i class='bx bxs-user absolute right-4'></i>
                            </div>
                        </div>

                        <div>
                            <label class="text-gray-800 text-sm mb-2 block">Password</label>
                            <div class="relative flex items-center">
                                <input id="password" name="password" type="password" required
                                    class="w-full text-gray-800 text-sm border border-gray-300 px-4 py-3 rounded-md outline-blue-600"
                                    placeholder="Enter password" />
                                <i id="togglePassword" class='bx bx-show absolute right-4 cursor-pointer'></i>
                            </div>
                        </div>

                        <div class="!mt-8">
                            <button type="submit"
                                class="w-full py-3 px-4 text-sm tracking-wide rounded-lg text-white bg-blue-600 hover:bg-blue-700 focus:outline-none">
                                Sign in
                            </button>
                        </div>
                        <p class="text-gray-800 text-sm !mt-8 text-center">Don't have an account? <a
                                onclick="window.location.href='{{ route('register') }}'"
                                class="text-blue-600 hover:underline ml-1 whitespace-nowrap font-semibold">Register
                                here</a></p>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $('#togglePassword').on('click', function() {
            const passwordField = $('#password');
            const type = passwordField.attr('type') === 'password' ? 'text' : 'password';
            passwordField.attr('type', type);
            $(this).toggleClass('bx-show bx-hide');
        });
    });
</script>
