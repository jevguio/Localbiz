<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        if (User::where('email', $request->email)->exists()) {
            session()->flash('error', 'This email is already registered.');
            return back()->withErrors(['email' => 'This email is already registered.'])->withInput();
        }
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'address' => $request->address,
            'phone' => $request->phone,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        Auth::login($user);

        if (Auth::user()->role == 'Customer') {
            return redirect()->intended(route('customer.products', absolute: false));
        } else if (Auth::user()->role == 'DeliveryRider') {
            return redirect()->intended(route('rider.orders', absolute: false));
        } else if (Auth::user()->role == 'Seller') {
            return redirect()->intended(route('seller.dashboard', absolute: false));
        } else if (Auth::user()->role == 'Cashier') {
            return redirect()->intended(route('cashier.dashboard', absolute: false));
        } else if (Auth::user()->role == 'GovernmentAgency') {
            return redirect()->intended(route('government.approval', absolute: false));
        } else if (Auth::user()->role == 'Owner') {
            return redirect()->intended(route('owner.account', absolute: false));
        }

        return redirect(route('dashboard', absolute: false));
    }
}
