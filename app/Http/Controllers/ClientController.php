<?php

namespace App\Http\Controllers;

use App\Services\ClientService;
use App\Http\Requests\CreateClientRequest;
use App\Http\Requests\UpdateClientRequest;
use App\Http\Requests\FilterClientRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class ClientController extends Controller
{
    public function __construct(private ClientService $clientService)
    {
    }

    public function create(CreateClientRequest $request): JsonResponse
    {
        return response()->json($this->clientService->create($request->validated()));
    }

    public function update(int $id, UpdateClientRequest $request): JsonResponse
    {
        return response()->json($this->clientService->update($id, $request->validated()));
    }

    public function delete(int $id): JsonResponse
    {
        return response()->json($this->clientService->delete($id));
    }

    public function getAll(FilterClientRequest $request): JsonResponse
    {
        return response()->json($this->clientService->getAll($request->validated()));
    }

    public function getClientsByUserId(FilterClientRequest $request): JsonResponse
    {
        return response()->json($this->clientService->getClientsByUserId(Auth::user()->id, $request->validated()));
    }

    public function find(int $id): JsonResponse
    {
        return response()->json($this->clientService->find($id));
    }
}
