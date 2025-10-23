<?php

namespace App\Repositories;

use App\Models\Event;
use Illuminate\Pagination\LengthAwarePaginator;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

class EventRepository
{
    public function create(array $data): Event
    {
        return Event::create($data);
    }

    public function find(int $id): ?Event
    {
        return Event::with('lead')->find($id);
    }

    public function update(int $id, array $data): ?Event
    {
        $event = $this->find($id);
        $event->update($data);
        return $event;
    }

    public function delete(int $id): void
    {
        Event::destroy($id);
    }

    private function filterEvents(Builder $query, array $filters = []): Builder
    {
        return $query
            ->when(isset($filters['lead_name']), function ($query) use ($filters) {
                $query->whereHas('lead', function ($query) use ($filters) {
                    $query->where('name', 'like', '%' . $filters['lead_name'] . '%');
                });
            })
            ->when(isset($filters['progress']), function ($query) use ($filters) {
                $query->when($filters['progress'] == 'overdue', function ($query) {
                    $query->where('next_call_date', '<', Carbon::now());
                })
                ->when($filters['progress'] == 'due_soon', function ($query) {
                    $query->where('next_call_date', '<=', Carbon::now()->addDays(2));
                })
                ->when($filters['progress'] === 'today', function ($query) {
                    $query->whereDate('next_call_date', Carbon::now()->toDateString())
                          ->whereTime('next_call_date', '>=', Carbon::now()->toTimeString());
                })
                ->when($filters['progress'] == 'on_time', function ($query) {
                    $query->where('next_call_date', '>=', Carbon::now()->addDays(2));
                });
            })
            ->when(isset($filters['next_call_date']), function ($query) use ($filters) {
                $formattedDate = Carbon::createFromFormat('d-m-Y', $filters['next_call_date'])
                    ->format('Y-m-d 00:00:00');

                $query->whereDate('next_call_date', $formattedDate);
            })
            ->when(isset($filters['next_call_date_from']) && isset($filters['next_call_date_to']), function ($query) use ($filters) {
                $from = Carbon::createFromFormat('d-m-Y', $filters['next_call_date_from'])
                    ->startOfDay()
                    ->format('Y-m-d H:i:s');

                $to = Carbon::createFromFormat('d-m-Y', $filters['next_call_date_to'])
                    ->endOfDay()
                    ->format('Y-m-d H:i:s');

                $query->whereBetween('next_call_date', [$from, $to]);
            })
            ->when(isset($filters['user_id']), function ($query) use ($filters) {
                $query->whereHas('lead', function ($query) use ($filters) {
                    $query->where('user_id', $filters['user_id']);
                });
            })
            ->when(isset($filters['lead_id']), function ($query) use ($filters) {
                $query->where('lead_id', $filters['lead_id']);
            })
            ->orderBy('next_call_date');
    }

    public function getAll(array $filters = []): LengthAwarePaginator
    {
        return $this->filterEvents(Event::query(), $filters)->with('lead')->paginate($filters['per_page'] ?? 10);
    }

    public function getEventsByUserId(int $userId, array $filters = []): LengthAwarePaginator
    {
        return $this->filterEvents(Event::query(), $filters)->with(['lead', 'lead.client'])->whereHas('lead', function ($query) use ($userId) {
            $query->whereHas('user', function ($query) use ($userId) {
                $query->where('id', $userId);
            });
        })
        ->paginate($filters['per_page'] ?? 10);
    }
}