<?php

namespace App\Http\Controllers;

use App\Services\LeadService;
use App\Http\Requests\CreateLeadRequest;
use App\Http\Requests\UpdateLeadRequest;
use Illuminate\Http\JsonResponse;

class LeadController extends Controller
{
    public function __construct(private LeadService $leadService)
    {
    }

    public function create(CreateLeadRequest $request): JsonResponse
    {
        return response()->json($this->leadService->create($request->validated()));
    }

    public function update(UpdateLeadRequest $request, int $id): JsonResponse
    {
        return response()->json($this->leadService->update($id, $request->validated()));
    }
    
    public function delete(int $id): JsonResponse
    {
        return response()->json($this->leadService->delete($id));
    }
    
    public function getAll(array $filters = []): JsonResponse
    {
        return response()->json($this->leadService->getAll($filters));
    }
    
    public function find(int $id): JsonResponse
    {
        return response()->json($this->leadService->find($id));
    }
    
}
