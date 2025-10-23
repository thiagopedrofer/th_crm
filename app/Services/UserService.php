<?php

namespace App\Services;

use App\Repositories\UserRepository;
use App\Enum\PrivilegeEnum;
use Illuminate\Support\Facades\Auth;
use App\Exceptions\UnauthorizedException;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;

class UserService
{
    public function __construct(private UserRepository $userRepository)
    {
    }

    public function register(array $data): User
    {
        $isAdminOrSuperAdmin = in_array($data['privilege_id'], [PrivilegeEnum::ADMIN->value, PrivilegeEnum::SUPER_ADMIN->value]);

        if ($isAdminOrSuperAdmin && Auth::user()->privilege_id !== PrivilegeEnum::SUPER_ADMIN->value) {
            throw new UnauthorizedException('You are not authorized to create an admin or super admin user!');
        }

        return $this->userRepository->create($data);
    }

    public function update(int $id, array $data): User
    {
        return $this->userRepository->update($id, $data);
    }

    public function delete(int $id): void
    {
        $this->userRepository->delete($id);
    }

    public function getAll(array $filters = []): LengthAwarePaginator
    {
        return $this->userRepository->getAll($filters);
    }

    public function find(int $id): User
    {
        return $this->userRepository->find($id);
    }
}