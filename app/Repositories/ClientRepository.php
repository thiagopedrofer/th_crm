<?php

namespace App\Repositories;

use App\Models\Client;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;

class ClientRepository
{
    public function create(array $data)
    {
        return Client::create($data);
    }

    public function update(int $id, array $data)
    {
        return Client::find($id)->update($data);
    }

    public function delete(int $id)
    {
        return Client::destroy($id);
    }

    private function filterClients(Builder $query, array $filters = []): Builder
    {
        return $query
        ->when(isset($filters['name']), function ($query) use ($filters) {
            $query->where('name', 'like', '%' . $filters['name'] . '%');
        })
        ->when(isset($filters['email']), function ($query) use ($filters) {
            $query->where('email', 'like', '%' . $filters['email'] . '%');
        })
        ->when(isset($filters['phone']), function ($query) use ($filters) {
            $query->where('phone', 'like', '%' . $filters['phone'] . '%');
        })
        ->when(isset($filters['cpf']), function ($query) use ($filters) {
            $query->where('cpf', 'like', '%' . $filters['cpf'] . '%');
        });
    }
    
    public function getClientsByUserId(int $userId, array $filters = []): LengthAwarePaginator
    {
        return $this->filterClients(Client::where('user_id', $userId), $filters)->paginate($filters['per_page'] ?? 10);
    }

    public function getAll(array $filters = []): LengthAwarePaginator
    {
        return $this->filterClients(Client::query(), $filters)->paginate($filters['per_page'] ?? 10);
    }

    public function find(int $id)
    {
        return Client::find($id);
    }
}