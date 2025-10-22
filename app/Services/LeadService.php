<?php

namespace App\Services;

use App\Repositories\LeadRepository;
use App\Repositories\EventRepository;
use App\Models\Lead;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class LeadService
{
    public function __construct(
        private LeadRepository $leadRepository,
        private EventRepository $eventRepository
    )
    {
    }

    public function create(array $data): Lead
    {
        $data['user_id'] = Auth::user()->id;
        $data['status'] = 'in_progress';

        $lead = $this->leadRepository->create($data);

        $this->eventRepository->create([
            'next_call_date' => Carbon::parse($data['next_call_date'])->format('Y-m-d H:i:s'),
            'notes' => $data['notes'],
            'lead_id' => $lead->id,
        ]);

        return $lead;
    }
    
    public function update(int $id, array $data): Lead
    {
        return $this->leadRepository->update($id, $data);
    }
    
    public function delete(int $id): void
    {
        $this->leadRepository->delete($id);
    }
    
    public function getAll(array $filters = []): LengthAwarePaginator
    {
        return $this->leadRepository->getAll($filters);
    }
    
    public function getLeadsByUserId(int $userId, array $filters = []): LengthAwarePaginator
    {
        return $this->leadRepository->getLeadsByUserId($userId, $filters);
    }
    
    public function find(int $id): Lead
    {
        return $this->leadRepository->find($id);
    }
}
