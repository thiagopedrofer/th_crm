<?php

namespace App\Services;

use App\Repositories\ClientRepository;
use Illuminate\Support\Facades\Auth;
use App\Models\Client;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Enum\PaymentStatusEnum;

class ClientService
{
    public function __construct(private ClientRepository $clientRepository)
    {
    }

    public function create(array $data): Client
    {
        $data['user_id'] = Auth::user()->id;
        $data['payment_status'] = PaymentStatusEnum::AWAITING_PAYMENT->value;
        return $this->clientRepository->create($data);
    }

    public function update(int $id, array $data): Client
    {
        return $this->clientRepository->update($id, $data);
    }
    
    public function delete(int $id): void
    {
        $this->clientRepository->delete($id);
    }

    public function getAll(array $filters = []): LengthAwarePaginator
    {
        return $this->clientRepository->getAll($filters);
    }

    public function getClientsByUserId(int $userId, array $filters = []): LengthAwarePaginator
    {
        return $this->clientRepository->getClientsByUserId($userId, $filters);
    }
    
    public function find(int $id): Client
    {
        return $this->clientRepository->find($id);
    }
}