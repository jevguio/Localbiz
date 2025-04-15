<x-guest-layout>
    <div class="bg-gray-50 font-[sans-serif]"
    style="background-image: url({{ asset('assets/img/background.png') }});background-repeat: no-repeat; background-position: center ; background-size: cover; height:100vh">
        <div class="min-h-screen flex flex-col items-center justify-center py-6 px-4">
            <div class="max-w-md w-full">
                <!-- <img src="{{ asset('assets/img/logo.jpg') }}" alt="logo" class="w-60 my-2 mx-auto"> -->
                <div class="p-8 rounded-2xl" style="background-color: rgba(20, 20, 20, 0.85)">
                    <h2 class="text-white text-center text-2xl font-bold">Register</h2>
                    <form class="mt-8 space-y-4" method="POST" action="{{ route('register') }}">
                        @csrf

                        <!-- Name -->
                        <div>
                            <label class="text-white text-sm mb-2 block">Name
                            <span class="text-red-500">*</span>
                            </label>
                            <div class="relative flex items-center">
                                <input id="name" name="name" type="text" required
                                    class="w-full text-white text-sm border border-gray-300 px-3 py-2 rounded-md outline-blue-600"
                                    placeholder="Enter name" />
                                <i class='bx bxs-user absolute right-4'></i>
                            </div>
                        </div>

                        <!-- Email Address -->
                        <div>
                            <label class="text-white text-sm mb-2 block">Email Address
                            <span class="text-red-500">*</span>
                            </label>
                            <div class="relative flex items-center">
                                <input id="email" name="email" type="email" required
                                    class="w-full text-white text-sm border border-gray-300 px-4 py-3 rounded-md outline-blue-600"
                                    placeholder="Enter email address" />
                                <i class='bx bxs-user absolute right-4'></i>
                            </div>
                        </div>

                        <!-- Address -->
                        <div>
                            <label class="text-white text-sm mb-2 block">Address</label>
                            <input id="address" name="address" type="text" required
                                class="w-full text-white text-sm border border-gray-300 px-4 py-3 rounded-md outline-blue-600"
                                placeholder="Enter address" />
                        </div>

                        <!-- Phone Number -->
                        <div>
                            <label class="text-white text-sm mb-2 block">Phone
                            <span class="text-red-500">*</span>
                            </label>
                            <input id="phone" name="phone" type="tel" required
                                class="w-full text-white text-sm border border-gray-300 px-4 py-3 rounded-md outline-blue-600"
                                placeholder="Enter phone number" maxlength="11" 
                                oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                                pattern="[0-9]{11}" />
                        </div>

                        <!-- Password -->
                        <div>
                            <label class="text-white text-sm mb-2 block">Password
                            <span class="text-red-500">*</span>
                            </label>
                            <div class="relative flex items-center">
                                <input id="password" name="password" type="password" required
                                    class="w-full text-white text-sm border border-gray-300 px-4 py-3 rounded-md outline-blue-600"
                                    placeholder="Enter password" />
                                <i id="togglePassword" class='bx bx-show absolute right-4 cursor-pointer'></i>
                            </div>
                        </div>

                        <!-- Confirm Password -->
                        <div>
                            <label class="text-white text-sm mb-2 block">Confirm Password
                            <span class="text-red-500">*</span>
                            </label>
                            <div class="relative flex items-center">
                                <input id="password_confirmation" name="password_confirmation" type="password" required
                                    class="w-full text-white text-sm border border-gray-300 px-4 py-3 rounded-md outline-blue-600"
                                    placeholder="Enter password" />
                                <i id="togglePasswordConfirmation"
                                    class='bx bx-show absolute right-4 cursor-pointer'></i>
                            </div>
                        </div>

                        <div class="!mt-8">
                            <button type="submit"
                                class="w-full py-3 px-4 text-sm tracking-wide rounded-lg text-white bg-blue-600 hover:bg-blue-700 focus:outline-none">
                                Register
                            </button>
                        </div>
                        <p class="text-white text-sm !mt-8 text-center">Already registered? <a
                                onclick="window.location.href='{{ route('login') }}'"
                                class="text-blue-600 hover:underline ml-1 whitespace-nowrap font-semibold">Sign in
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

        $('#togglePasswordConfirmation').on('click', function() {
            const passwordField = $('#password_confirmation');
            const type = passwordField.attr('type') === 'password' ? 'text' : 'password';
            passwordField.attr('type', type);
            $(this).toggleClass('bx-show bx-hide');
        });

        $('#phone').on('keypress', function(e) {
            if (e.which < 48 || e.which > 57) {
                e.preventDefault();
            }
        });
    });
</script>
