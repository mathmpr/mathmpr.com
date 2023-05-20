<?php

namespace App\Http\Controllers\Web\Backend;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;

class LoginController extends Controller
{
    public function view(): View|Factory
    {
        return Controller::autoDiscoverView('login');
    }

    public function login(Request $request): RedirectResponse
    {
        $user = User::where([
            'email' => $request->get('username')
        ])->first();
        if ($user && Hash::check($request->get('password'), $user->password)) {
            auth()->login($user);
        }

        $to = App::getLocale() . '/dashboard';

        if (!auth()->user()) {
            session()->flash('wrong-credentials');
            $to = App::getLocale() . '/login';
        }
        return redirect($to);
    }
}
