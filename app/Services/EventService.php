<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use App\Repositories\EventRepository;
use Illuminate\Pagination\LengthAwarePaginator;
use Carbon\Carbon;
use App\Models\Event;

class EventService
{
    public function __construct(private EventRepository $eventRepository)
    {
    }

    public function getAll(array $filters = []): LengthAwarePaginator
    {
        $events = $this->eventRepository->getAll($filters);

        $events->getCollection()->transform(function ($event) {
            $nextCallDate = Carbon::parse($event->next_call_date);
            $event->progress = $this->getProgress($nextCallDate);
        
            return $event;
        });

        return $events;
    }

    public function find(int $id): Event
    {
        return $this->eventRepository->find($id);
    }

    public function create(array $data)
    {
        $event = $this->eventRepository->create($data);

        return $event;
    }

    public function update(int $id, array $data): Event
    {
        return $this->eventRepository->update($id, $data);
    }

    public function delete(int $id): void
    {
        $this->eventRepository->delete($id);
    }

    private function getProgress(Carbon $nextCallDate): string
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
    
    public function getEventsByUserId(array $filters = []): LengthAwarePaginator
    {
        $userId = Auth::user()->id;

        $events = $this->eventRepository->getEventsByUserId($userId, $filters);

        $events->getCollection()->transform(function ($event) {
            $nextCallDate = Carbon::parse($event->next_call_date);
            $event->progress = $this->getProgress($nextCallDate);
        
            return $event;
        });

        return $events;
    }

}