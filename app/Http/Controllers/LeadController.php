<?php

namespace App\Http\Controllers;

use App\Services\LeadService;
use App\Http\Requests\CreateLeadRequest;
use App\Http\Requests\UpdateLeadRequest;
use Illuminate\Support\Facades\Auth;
class LeadController extends Controller
{
    public function __construct(private LeadService $leadService)
    {
    }

    public function create(CreateLeadRequest $request)
    {
        return $this->leadService->create($request->validated());
    }

    public function update(UpdateLeadRequest $request, int $id)
    {
        return $this->leadService->update($id, $request->validated());
    }
    
    public function delete(int $id)
    {
        return $this->leadService->delete($id);
    }
    
    public function getAll(array $filters = [])
    {
        return $this->leadService->getAll($filters);
    }
    
    public function find(int $id)
    {
        return $this->leadService->find($id);
    }
    
}
