<?php

namespace App\Http\Controllers;

use App\Services\LeadService;
use App\Http\Requests\CreateLeadRequest;
use App\Http\Requests\UpdateLeadRequest;
use App\Http\Requests\FilterLeadsRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

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
    
    public function destroy(int $id): JsonResponse
    {
        return response()->json($this->leadService->delete($id));
    }
    
    public function index(FilterLeadsRequest $request): JsonResponse
    {
        return response()->json($this->leadService->getAll($request->validated()));
    }
    
    public function show(int $id): JsonResponse
    {
        return response()->json($this->leadService->find($id));
    }
    
    public function getLeadsByUserId(FilterLeadsRequest $request): JsonResponse
    {
        return response()->json($this->leadService->getLeadsByUserId(Auth::user()->id, $request->validated()));
    }
    
}
