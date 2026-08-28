<?php

namespace App\Http\Controllers\Web\Backend;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;

class LoginController extends Controller
{
    private const MAX_LOGIN_ATTEMPTS = 3;
    private const LOGIN_LOCK_SECONDS = 21600;

    public function view(): View|Factory
    {
        return Controller::autoDiscoverView('login');
    }

    public function login(Request $request): RedirectResponse
    {
        $throttleKey = $this->throttleKey($request);

        if (RateLimiter::tooManyAttempts($throttleKey, self::MAX_LOGIN_ATTEMPTS)) {
            return $this->redirectToLoginWithLockMessage($throttleKey);
        }

        /**
         * @var User $user
         */
        $user = User::where([
            'email' => $request->get('username')
        ])->first();
        if ($user && Hash::check($request->get('password'), $user->password)) {
            RateLimiter::clear($throttleKey);
            auth()->login($user);
            $token = $user->generateToken();
            session()->put('api-key', $token);
        }

        $to = App::getLocale() . '/dashboard/nodes';

        if (!auth()->user()) {
            RateLimiter::hit($throttleKey, self::LOGIN_LOCK_SECONDS);

            if (RateLimiter::tooManyAttempts($throttleKey, self::MAX_LOGIN_ATTEMPTS)) {
                return $this->redirectToLoginWithLockMessage($throttleKey);
            }

            session()->flash('wrong-credentials');
            $to = App::getLocale() . '/login';
        }
        return redirect($to);
    }

    private function throttleKey(Request $request): string
    {
        return 'backend-login:' . sha1(Str::lower((string) $request->ip()));
    }

    private function redirectToLoginWithLockMessage(string $throttleKey): RedirectResponse
    {
        session()->flash('login-locked', trans('backend.login.locked', [
            'hours' => (int) ceil(RateLimiter::availableIn($throttleKey) / 3600),
        ]));

        return redirect(App::getLocale() . '/login');
    }
}
