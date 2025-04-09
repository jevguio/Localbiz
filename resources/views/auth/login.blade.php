<x-guest-layout>
    <div class="bg-gray-50 font-[sans-serif] p-10"
        style="background-image: url({{ asset('assets/img/background.png') }});background-repeat: no-repeat; background-position: center ; background-size: cover; height:100vh">
        <div class=" flex flex-col items-center justify-center py-6 px-4">
            <div class="w-full flex p-10 m-10 ">
                <div class="p-8 w-full  p-10 m-10 "
                    style="max-width: 50%;color:white;text-shadow: -1px -1px 3px #000, 1px -1px 3px #000, -1px 1px 3px #000, 1px 1px 3px #000;margin-top:-2%">
                    <div style="font-size: 6rem; font-weight: bolder; text-align: center;">Welcome To<br /> LocalBiz
                    </div>
                    <p style="text-align: center; font-size: x-large;">Support Local</p>
                </div>
                <div class="p-8 rounded-2xl w-full max-w-md  shadow  p-10 m-10 " style="background-color: rgba(158, 158, 158, 0.616)">
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
                                    placeholder="Enter password" id="cpass" />
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
