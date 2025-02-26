<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $user = Auth::user();

        if ($user->is_active == 0) {
            Auth::logout();
            return redirect()
                ->route('login')
                ->with('error', 'Your account is not active. Please contact the administrator.');
        }

        $request->session()->regenerate();

        if ($user->role == 'Customer') {
            return redirect()->intended(route('customer.products', absolute: false));
        } elseif ($user->role == 'DeliveryRider') {
            return redirect()->intended(route('rider.orders', absolute: false));
        } elseif ($user->role == 'Seller') {
            return redirect()->intended(route('seller.dashboard', absolute: false));
        } elseif ($user->role == 'Cashier') {
            return redirect()->intended(route('cashier.dashboard', absolute: false));
        } elseif ($user->role == 'GovernmentAgency') {
            return redirect()->intended(route('government.approval', absolute: false));
        } elseif ($user->role == 'Owner') {
            return redirect()->intended(route('owner.account', absolute: false));
        }

        return redirect()->intended(route('dashboard', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $user = Auth::user();

        if ($user instanceof User) {
            $user->last_login = now();
            $user->save();
        }

        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
