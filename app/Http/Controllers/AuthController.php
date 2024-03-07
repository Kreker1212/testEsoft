<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegistrationRequest;
use Illuminate\Routing\Redirector;

class AuthController extends Controller
{
    public function showLoginForm(): View
    {
        return view('auth.login');
    }

    public function login(LoginRequest $req): RedirectResponse
    {
        $data = [
            'login' => $req->login,
            'password' => $req->password
        ];

        if (auth('web')->attempt($data)) {

            return redirect(route('show.home'));
        }

        return redirect(route('login'))->withErrors(['login' => 'Пользователя с таким логином не существует или
         пользователь ввел неверный пароль']);
    }

    public function logout(): RedirectResponse
    {
        auth('web')->logout();

        return redirect(route('login'));
    }

    public function showRegisterForm(): View
    {
        return view('auth.register');
    }

    public function register(RegistrationRequest $req): Redirector
    {
        $user = User::create([
            'surname' => $req->surname,
            'name' => $req->name,
            'last_name' => $req->last_name,
            'login' => $req->login,
            'email' => $req->email,
            'password' => bcrypt($req->password)
        ]);

        if ($user) {
            auth('web')->login($user);
        }

        return redirect(route('show.home'));
    }
}
