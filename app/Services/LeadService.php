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

    public function getLeadsWithEvents(array $filters = []): LengthAwarePaginator
    {
        $leads = $this->leadRepository->getLeadsWithEvents($filters);

        $leads->getCollection()->transform(function ($lead) {
            if ($lead->events) {
                $lead->events->transform(function ($event) {
                    $nextCallDate = Carbon::parse($event->next_call_date);
                    $event->progress = $this->getEventProgress($nextCallDate);
                    return $event;
                });
            }
            return $lead;
        });

        return $leads;
    }

    public function getLeadsWithEventsByUserId(array $filters = []): LengthAwarePaginator
    {
        $userId = Auth::user()->id;

        // Buscar leads do usuário com eventos usando o método específico
        $leads = $this->leadRepository->getLeadsWithEventsByUserId($userId, $filters);

        // Transformar cada lead para incluir progress nos eventos
        $leads->getCollection()->transform(function ($lead) {
            if ($lead->events) {
                $lead->events->transform(function ($event) {
                    $nextCallDate = Carbon::parse($event->next_call_date);
                    $event->progress = $this->getEventProgress($nextCallDate);
                    return $event;
                });
            }
            return $lead;
        });

        return $leads;
    }

    private function getEventProgress(Carbon $nextCallDate): string
    {
        $now = Carbon::now();

        if ($nextCallDate->lt($now)) {
            return 'overdue';
        }

        if ($nextCallDate->isSameDay($now) && $nextCallDate->gte($now)) {
            return 'today';
        }

        if ($nextCallDate->lte($now->copy()->addDays(2))) {
            return 'due_soon';
        }

        return 'on_time';
    }
}
