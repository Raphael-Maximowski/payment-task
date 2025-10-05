<?php
namespace App\Repositories\Contracts;
use App\Models\Payment;

interface PaymentRepositoryInterface
{
    public function create(array $data): Payment; // Somente a assinatura do método 
    public function findById(int $id): ?Payment;
}
