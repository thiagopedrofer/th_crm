<?php

namespace App\Repositories;

use App\Models\Lead;
use Illuminate\Database\Eloquent\Collection;

class LeadRepository
{
    public function create(array $data): Lead
    {
        return Lead::create($data);
    }

    public function find(int $id): Lead
    {
        return Lead::find($id);
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

    public function getAll(array $filters = []): Collection
    {
        return Lead::whereAny(
            ['name', 'email', 'phone', 'address', 'city', 'state', 'zip'],
            'like',
            '%' . $filters['search'] . '%'
        )->paginate($filters['per_page'] ?? 10);
    }
}