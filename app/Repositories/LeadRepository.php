<?php

namespace App\Repositories;

use App\Models\Lead;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Carbon\Carbon;

class LeadRepository
{
    public function create(array $data): Lead
    {
        return Lead::create($data);
    }

    public function find(int $id): Lead
    {
        return Lead::with('events')->findOrFail($id);
    }

    public function update(int $id, array $data): Lead
    {
        $lead = $this->find($id);
        $lead->update($data);
        return $lead;
    }
    
    public function delete(int $id): void
    {
        Lead::destroy($id);
    }

    private function filterLeads(Builder $query, array $filters): Builder
    {
        return $query->when(isset($filters['name']), function ($query) use ($filters) {
            $query->where('name', 'like', '%' . $filters['name'] . '%');
        })
        ->when(isset($filters['city']), function ($query) use ($filters) {
            $query->where('city', 'like', '%' . $filters['city'] . '%');
        })
        ->when(isset($filters['state']), function ($query) use ($filters) {
            $query->where('state', 'like', '%' . $filters['state'] . '%');
        })
        ->when(isset($filters['status']), function ($query) use ($filters) {
            $query->where('status', 'like', '%' . $filters['status'] . '%');
        })
        ->when(isset($filters['lead_type_id']), function ($query) use ($filters) {
            $query->where('lead_type_id', $filters['lead_type_id']);
        })
        ->when(isset($filters['desired_credit_amount']), function ($query) use ($filters) {
            $query->where('desired_credit_amount', $filters['desired_credit_amount']);
        })
        ->when(isset($filters['user_id']), function ($query) use ($filters) {
            $query->where('user_id', $filters['user_id']);
        });
    }

    public function getAll(array $filters = []): LengthAwarePaginator
    {
        return $this->filterLeads(Lead::query()->orderBy('name')->with('events'), $filters)->paginate($filters['per_page'] ?? 15);
    }

    public function getLeadsByUserId(int $userId, array $filters = []): LengthAwarePaginator
    {
        return $this->filterLeads(Lead::query()->where('user_id', $userId)->orderBy('name')->with('events'), $filters)
        ->paginate($filters['per_page'] ?? 15);
    }

    private function filterEvents(Builder $query, array $filters = []): Builder
    {
        return $query
            ->when(isset($filters['progress']), function ($query) use ($filters) {
                $query->whereHas('events', function ($query) use ($filters) {
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
                });
            })
            ->when(isset($filters['next_call_date']), function ($query) use ($filters) {
                $query->whereHas('events', function ($query) use ($filters) {
                    $formattedDate = Carbon::createFromFormat('d-m-Y', $filters['next_call_date'])
                        ->format('Y-m-d 00:00:00');
                    $query->whereDate('next_call_date', $formattedDate);
                });
            })
            ->when(isset($filters['next_call_date_from']) && isset($filters['next_call_date_to']), function ($query) use ($filters) {
                $query->whereHas('events', function ($query) use ($filters) {
                    $from = Carbon::createFromFormat('d-m-Y', $filters['next_call_date_from'])
                        ->startOfDay()
                        ->format('Y-m-d H:i:s');
                    $to = Carbon::createFromFormat('d-m-Y', $filters['next_call_date_to'])
                        ->endOfDay()
                        ->format('Y-m-d H:i:s');
                    $query->whereBetween('next_call_date', [$from, $to]);
                });
            })
            ->when(isset($filters['lead_id']), function ($query) use ($filters) {
                $query->where('id', $filters['lead_id']);
            });
    }

    public function getLeadsWithEvents(array $filters = []): LengthAwarePaginator
    {
        return $this->filterEvents(Lead::query()->with(['events' => function ($query) {
            $query->orderBy('next_call_date', 'asc');
        }]), $filters)
            ->paginate($filters['per_page'] ?? 15);
    }

    public function getLeadsWithEventsByUserId(int $userId, array $filters = []): LengthAwarePaginator
    {
        return $this->filterEvents(Lead::query()->where('user_id', $userId)->with(['events' => function ($query) {
            $query->orderBy('next_call_date', 'asc');
        }]), $filters)
            ->paginate($filters['per_page'] ?? 15);
    }
}