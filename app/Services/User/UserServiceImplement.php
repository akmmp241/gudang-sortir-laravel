<?php

namespace App\Services\User;

use App\Exceptions\ValidationUserException;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\UpdatePasswordRequest;
use App\Http\Requests\UpdateProfileRequest;
use App\Models\User;
use App\Repositories\User\UserRepository;
use Exception;
use Illuminate\Support\Facades\Hash;
use LaravelEasyRepository\Service;

class UserServiceImplement extends Service implements UserService
{
    protected UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    // Define your custom methods :)

    /**
     * @throws ValidationUserException
     * @throws Exception
     */
    public function register(RegisterRequest $request): void
    {
        RegisterRequest::validating($request, $this->userRepository);

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);

        $this->userRepository->save($user);
    }

    /**
     * @throws ValidationUserException
     */
    public function login(LoginRequest $request): ?User
    {
        LoginRequest::validating($request);

        $info = $this->userRepository->auth([
            'email' => $request->email,
            'password' => $request->password
        ]);

        if (!$info) {
            throw ValidationUserException::loginFailed();
        }

        return $this->userRepository->findByEmail($request->email);
    }

    /**
     * @throws ValidationUserException
     */
    public function updatePassword(UpdatePasswordRequest $request): void
    {
        UpdatePasswordRequest::validating($request, $this->userRepository);

        $user = $this->userRepository->findById($request->id);
        $user->password = Hash::make($request->newPassword);

        $this->userRepository->updating($user);
    }

    /**
     * @throws ValidationUserException
     */
    public function updateProfile(UpdateProfileRequest $request): void
    {
        UpdateProfileRequest::validating($request);

        $user = $this->userRepository->findById($request->id);
        $user->name = $request->name;
        $user->email = $request->email;

        $this->userRepository->save($user);
    }
}
