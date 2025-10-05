<?php

namespace App\Repositories\Contracts;

interface FraudeServiceInterface
{
    public function isFraudulent(array $data): bool;
}