<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;

class UserRepository
{
    public function create(array $data): User
    {
        return User::create($data);
    }

    public function find(int $id): User
    {
        return User::find($id);
    }

    public function findByEmail(string $email): ?User
    {
        return User::where('email', $email)->first();
    }

    public function update(int $id, array $data): User
    {
        $user = $this->find($id);
        $user->update($data);
        return $user;
    }
    
    public function delete(int $id): void
    {
        User::destroy($id);
    }

    public function getAll(array $filters = []): LengthAwarePaginator
    {
        return User::when(isset($filters['privilege_id']), function ($query) use ($filters) {
            $query->where('privilege_id', $filters['privilege_id']);
        })->when(isset($filters['search']), function ($query) use ($filters) {
            $query->where('name', 'like', '%' . $filters['search'] . '%')
                ->orWhere('email', 'like', '%' . $filters['search'] . '%');
        })->when(isset($filters['status']), function ($query) use ($filters) {
            $query->where('status', $filters['status']);
        })
        ->paginate($filters['per_page'] ?? 10);
    }
}