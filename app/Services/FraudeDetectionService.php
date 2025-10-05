<?php

namespace App\Services;

class FraudeDetectionService 
{
    public function isFraudulent(): bool
    {
        $probability = rand(1, 100) / 100;
        $idFraude = $probability > 0.2 ? false : true;

        return $idFraude;

    }
}