<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use App\Repositories\EventRepository;
use Illuminate\Pagination\LengthAwarePaginator;
use Carbon\Carbon;

class EventService
{
    public function __construct(private EventRepository $eventRepository)
    {
    }

    public function getAll(array $filters = [])
    {
        return $this->eventRepository->getAll($filters);
    }

    public function find(int $id)
    {
        return $this->eventRepository->find($id);
    }

    public function create(array $data)
    {
        $event = $this->eventRepository->create($data);

        $event->leads()->attach($data['lead_id']);

        return $event;
    }

    public function update(int $id, array $data)
    {
        return $this->eventRepository->update($id, $data);
    }

    public function delete(int $id)
    {
        return $this->eventRepository->delete($id);
    }
    
    public function getEventsByUserId(array $filters = [])
    {
        $userId = Auth::user()->id;

        $events = $this->eventRepository->getEventsByUserId($userId, $filters);

        $events->getCollection()->transform(function ($event) {
            $nextCallDate = Carbon::parse($event->next_call_date);

            if ($nextCallDate->lt(Carbon::now())) {
                $event->progress = 'overdue';
            } elseif ($nextCallDate->lte(Carbon::now()->addDays(2))) {
                $event->progress = 'due_soon';
            } else {
                $event->progress = 'on_time';
            }

            return $event;
        });

        return $events;
    }

}