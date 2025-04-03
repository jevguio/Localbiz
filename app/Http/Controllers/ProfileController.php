<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        try {
            $user = $request->user();
            $validated = $request->validated();
            
            // Debug incoming data
            \Log::info('Update data:', [
                'last_name' => $request->last_name,
                'validated' => $validated,
                'current_last_name' => $user->last_name
            ]);

            $user->fill($validated);
            
            // Explicitly set last name
            if (isset($validated['last_name'])) {
                $user->last_name = $validated['last_name'];
            }

            if ($request->hasFile('avatar')) {
                $avatar = $request->file('avatar');
                $filename = time() . '_' . $avatar->getClientOriginalName();
                $avatar->move(public_path('avatar'), $filename);
                $user->avatar = $filename;
            }

            // Debug changes before saving
            \Log::info('Changes to save:', $user->getDirty());

            $user->save();
            
            session()->flash('success', 'Profile Updated Successfully');
            return Redirect::route('profile.edit')->with('status', 'profile-updated');
            
        } catch(Exception $e) {
            \Log::error('Profile update error: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Failed to update profile');
        }
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
