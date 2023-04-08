<?php

namespace App\Http\Controllers;

use App\Exceptions\ValidationUserException;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\UpdatePasswordRequest;
use App\Services\Session\SessionService;
use App\Services\Session\SessionServiceImplement;
use App\Services\User\UserService;
use App\Services\User\UserServiceImplement;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class UserController extends Controller
{
    private UserServiceImplement $userService;
    private SessionServiceImplement $sessionService;


    public function __construct()
    {
        $this->userService = app()->make(UserService::class);
        $this->sessionService = app()->make(SessionService::class);
    }

    public function register(): View
    {
        return view('User.register', [
            'title' => 'register'
        ]);
    }

    public function postRegister(RegisterRequest $request): RedirectResponse
    {
        try {
            $this->userService->register($request);
            return redirect('/users/login');
        } catch (Exception $exception) {
            return back()->withErrors(['error' => $exception->getMessage()]);
        }
    }

    public function login(): View
    {
        return view('User.login', [
            'title' => 'Login'
        ]);
    }

    public function postLogin(LoginRequest $request): RedirectResponse
    {
        try {
            $user = $this->userService->login($request);
            $this->sessionService->creating($user->id);
            return redirect()->intended('/dashboard');
        } catch (ValidationUserException $exception) {
            return back()->withErrors(['error' => $exception->getMessage()]);
        }
    }

    public function updatePassword(): View
    {
        return view('User.password', [
            'title' => 'update password'
        ]);
    }

    public function postUpdatePassword(UpdatePasswordRequest $request): RedirectResponse
    {
        $userId = $this->sessionService->current()->id;
        $request->id = $userId;
        try {
            $this->userService->updatePassword($request);
            return redirect('/dashboard');
        } catch (ValidationUserException $e) {
            return back()->withErrors(['error' => $e->getMessage]);
        }
    }
}
