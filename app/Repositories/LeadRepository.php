<?php

namespace App\Repositories;

use App\Models\Lead;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;

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
}