<?php

namespace App\Services;

use App\Repositories\UserRepository;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Exceptions\ModelNotFoundException;
use App\Exceptions\UnauthorizedException;

class AuthService
{
    public function __construct(
        private UserRepository $userRepository
    ) 
    {}

    public function me(): User
    {
        $email = Auth::user()->email;
        return $this->userRepository->findByEmail($email);
    }

    public function login(array $data)
    {
        $user = $this->userRepository->findByEmail($data['email']);


        if (!$user) {
            throw new ModelNotFoundException('User not found!');
        }

        if (!Hash::check($data['password'], $user->password)) {
            throw new UnauthorizedException('Data credentials are incorrect!');
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return [
            'user' => $user,
            'token' => $token,
        ];
    }
}