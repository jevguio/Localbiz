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

        <fieldset class="fieldset">
            <legend class="fieldset-legend">Current Password</legend>
            <input type="password" name="current_password" class="input w-full" placeholder="Type your current password"
                autocomplete="current-password" />
        </fieldset>

        <fieldset class="fieldset">
            <legend class="fieldset-legend">New Password</legend>
            <input type="password" name="password" class="input w-full" placeholder="Type your new password"
                autocomplete="new-password" />
        </fieldset>

        <fieldset class="fieldset">
            <legend class="fieldset-legend">Confirm Password</legend>
            <input type="password" name="password_confirmation" class="input w-full"
                placeholder="Type your confirm password" autocomplete="new-password" />
        </fieldset>

        <div class="flex items-center gap-4">
            <button type="submit"
                class="flex w-full items-center justify-center rounded-lg btn btn-primary px-5 py-2.5 text-sm font-medium text-white">Save</button>
        </div>
    </form>
</section>
