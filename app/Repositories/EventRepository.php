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
        return Event::find($id);
    }

    public function update(int $id, array $data): ?Event
    {
        return Event::find($id)->update($data);
    }

    public function delete(int $id): void
    {
        Event::destroy($id);
    }

    private function filterEvents(Builder $query, array $filters = []): Builder
    {
        return $query
            ->when(isset($filters['notes']), function ($query) use ($filters) {
                $query->where('notes', 'like', '%' . $filters['notes'] . '%');
            })
            ->when(isset($filters['progress']), function ($query) use ($filters) {
                $query->when($filters['progress'] == 'overdue', function ($query) {
                    $query->where('next_call_date', '<', Carbon::now());
                })
                ->when($filters['progress'] == 'due_soon', function ($query) {
                    $query->where('next_call_date', '<=', Carbon::now()->addDays(2));
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
            ->orderBy('next_call_date');
    }

    public function getAll(array $filters = []): LengthAwarePaginator
    {
        return $this->filterEvents(Event::query(), $filters)->paginate($filters['per_page'] ?? 10);
    }

    public function getEventsByUserId(int $userId, array $filters = []): LengthAwarePaginator
    {
        return $this->filterEvents(Event::query(), $filters)->with('leads')->whereHas('leads', function ($query) use ($userId) {
            $query->where('leads.user_id', $userId);
        })
        ->paginate($filters['per_page'] ?? 10);
    }
}