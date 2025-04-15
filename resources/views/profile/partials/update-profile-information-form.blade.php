<section  >
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __("Update your account's profile information and password.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="POST" action="{{ route('profile.update') }}" class="mt-6 space-y-6" enctype="multipart/form-data">
        @csrf
        @method('patch')
        <img src="{{ asset('avatar/' . $user->avatar) }}" alt="Profile Picture" class="w-60">
        <fieldset class="fieldset">
            <legend class="fieldset-legend">Profile Picture</legend>
            <input type="file" name="avatar" class="file-input file-input-bordered w-full" accept="image/*" />
        </fieldset>
        
        <fieldset class="fieldset">
            <legend class="fieldset-legend">{{ $user->role=="Seller"?"Seller Business Name":"First Name" }}</legend>
            <input type="text" name="fname" class="input w-full" placeholder="Type your name"
                value="{{ $user->fname }}" required />
        </fieldset>
        @if($user->role!="Seller")
        <fieldset class="fieldset">
            <legend class="fieldset-legend">Last Name</legend>
            <input type="text" name="lname" class="input w-full" placeholder="Type your name"
                value="{{ $user->lname }}" required />
        </fieldset>
        @endif
        <fieldset class="fieldset">
            <legend class="fieldset-legend">Email</legend>
            <input type="email" name="email" class="input w-full" placeholder="Type your email"
                value="{{ $user->email }}" required />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !$user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800">
                        {{ __('Your email address is unverified.') }}

                        <button form="send-verification"
                            class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </fieldset>

        <fieldset class="fieldset">
            <legend class="fieldset-legend">Address</legend>
            <input type="text" name="address" class="input w-full" placeholder="Type your address"
                value="{{ $user->address }}" required />
        </fieldset>

        <fieldset class="fieldset">
            <legend class="fieldset-legend">Phone</legend>
            <input type="tel" name="phone" class="input w-full" placeholder="Type your phone number"
                value="{{ $user->phone }}" required maxlength="11"
                oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                pattern="[0-9]{11}" />
        </fieldset>

        @if ($user->role == 'Seller')
            <fieldset class="fieldset">
                <legend class="fieldset-legend">GCash Number</legend>
                <input type="text" name="gcash_number" class="input w-full" placeholder="Type your GCash number"
                    value="{{ $user->gcash_number }}" required />
            </fieldset>
            <fieldset class="fieldset">
                <legend class="fieldset-legend">Bank Name</legend>
                <input type="text" name="bank_name" class="input w-full" placeholder="Type your bank name"
                    value="{{ $user->bank_name }}" required />
            </fieldset>
            <fieldset class="fieldset">
                <legend class="fieldset-legend">Bank Account Number</legend>
                <input type="text" name="bank_account_number" class="input w-full"
                    placeholder="Type your bank account number" value="{{ $user->bank_account_number }}" required />
            </fieldset>
        @endif

        <div class="flex items-center gap-4 mt-4">
            <button type="submit"
                class="flex w-full items-center justify-center rounded-lg btn btn-primary px-5 py-2.5 text-sm font-medium text-white">Save</button>
        </div>
    </form>
    <script>
        document.querySelector('input[name="phone"]').addEventListener('keypress', function(e) {
            if (e.which < 48 || e.which > 57) {
                e.preventDefault();
            }
        });
    </script>
    </section>
