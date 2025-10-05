<?php

namespace App\Services;

use App\Repositories\Contracts\FraudeServiceInterface;
use Illuminate\Support\Facades\Http;

class FraudeDetectionService implements FraudeServiceInterface
{
    public function isFraudulent(array $data): bool
    {
        // Http::post('https://api.fraude.falsa/check', [
        //     'amount' => $data['amount'],
        //     'card_number' => $data['card_number']
        // ]);

        $probability = rand(1, 100) / 100;
        $idFraude = $probability > 0.2 ? false : true;

        return $idFraude;

    }
}