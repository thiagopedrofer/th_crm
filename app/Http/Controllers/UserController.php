<?php

namespace App\Http\Controllers;

use App\Services\UserService;
use App\Http\Requests\RegisterRequest;

class UserController extends Controller
{
    public function __construct(private UserService $userService)
    {
    }

    public function register(RegisterRequest $request)
    {
        return $this->userService->register($request->validated());
    }
}
