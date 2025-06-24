<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Services\CartService;

class AuthController extends Controller
{
    /**
     * @var CartService
     */
    protected $cartService;

    /**
     * AuthController constructor.
     * @param CartService $cartService
     */
    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $this->cartService->assignCartToUser(
                $request->cookie('cart_token'), Auth::id()
            );

            return response()->json(['success' => true]);
        }

        return response()->json(['message' => 'Unauthorized'], 401);
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application
     *     |\Illuminate\Http\RedirectResponse
     *     |\Illuminate\Routing\Redirector
     */
    public function fakeLogin()
    {
        $user = User::find(1);

        if (!$user) {
            return redirect('/')->with('error', 'Test user not found.');
        }

        Auth::login($user);

        return redirect('/')->with('success', 'Logged in as test user.');
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application
     *     |\Illuminate\Http\RedirectResponse
     *     |\Illuminate\Routing\Redirector
     */
    public function logout()
    {
        Auth::logout();

        return redirect('/');
    }
}
