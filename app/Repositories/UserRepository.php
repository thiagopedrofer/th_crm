<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

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

    public function findByEmail(string $email): User
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

    public function getAll(array $filters = []): Collection
    {
        return User::whereAny(
            ['name', 'email', 'phone', 'address', 'city', 'state', 'zip'],
            'like',
            '%' . $filters['search'] . '%'
        )->paginate($filters['per_page'] ?? 10);
    }
}