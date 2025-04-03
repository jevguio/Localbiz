<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Update Password') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __('Ensure your account is using a long, random password to stay secure.') }}
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('put')

        <fieldset class="fieldset relative">
            <legend class="fieldset-legend">Current Password</legend>
            <input type="password" name="current_password" class="input w-full" placeholder="Type your current password"
                autocomplete="current-password" id="fpass" />
            <button type="button" class="absolute inset-y-0 right-0 flex items-center px-3"
                onclick="togglePassword('fpass','fpass_eye-icon')">
               
                <i id="fpass_eye-icon" class='bx bx-show absolute right-4 cursor-pointer'></i>
            </button>
        </fieldset>

        <fieldset class="fieldset relative">
            <legend class="fieldset-legend">New Password</legend>
            <input type="password" name="password" class="input w-full" placeholder="Type your new password"
                autocomplete="new-password" id="npass" />
            <button type="button" class="absolute inset-y-0 right-0 flex items-center px-3"
                onclick="togglePassword('npass','npass_eye-icon')">
                <i id="npass_eye-icon" class='bx bx-show absolute right-4 cursor-pointer'></i>
                    
            </button>
        </fieldset>

        <fieldset class="fieldset relative">
            <legend class="fieldset-legend">Confirm Password</legend>
            <input type="password" name="password_confirmation" class="input w-full"
                placeholder="Type your confirm password" autocomplete="new-password" id="cpass" />
            <button type="button" class="absolute inset-y-0 right-0 flex items-center px-3"
                onclick="togglePassword('cpass','cpass_eye-icon')">

                <i id="cpass_eye-icon" class='bx bx-show absolute right-4 cursor-pointer'></i>
            </button>
        </fieldset>

        <div class="flex items-center gap-4">
            <button type="submit"
                class="flex w-full items-center justify-center rounded-lg btn btn-primary px-5 py-2.5 text-sm font-medium text-white">Save</button>
        </div>
    </form>
 
    <script>
        function togglePassword(id, eye) {
            const passwordInput = document.getElementById(id);
            const eyeIcon = document.getElementById(eye); 
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.setAttribute('class', 'bx bx-hide absolute right-4 cursor-pointer'); // Change icon to open eye
            } else {
                passwordInput.type = 'password';
                eyeIcon.setAttribute('class',
                    'bx bx-show absolute right-4 cursor-pointer'
                    ); // Change icon back to closed eye
            }
        }
    </script>
</section>
