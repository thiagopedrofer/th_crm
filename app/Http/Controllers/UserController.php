<?php

namespace App\Http\Controllers;

use App\Services\UserService;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\UpdateUserRequest;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    public function __construct(private UserService $userService)
    {
    }

    public function register(RegisterRequest $request): JsonResponse
    {
        return response()->json($this->userService->register($request->validated()));
    }

    public function show(int $id): JsonResponse
    {
        return response()->json($this->userService->find($id));
    }

    public function index(): JsonResponse
    {
        return response()->json($this->userService->getAll());
    }

    public function update(int $id, UpdateUserRequest $request): JsonResponse
    {
        return response()->json($this->userService->update($id, $request->validated()));
    }

    public function destroy(int $id): JsonResponse
    {
        return response()->json($this->userService->delete($id));
    }
}
