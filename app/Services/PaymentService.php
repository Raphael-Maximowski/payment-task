<?php
namespace App\Services;
use App\Repositories\Contracts\PaymentRepositoryInterface;
use Illuminate\Support\Str;

class PaymentService
{
    protected $paymentRepository; 
    protected $fraudeService; 

    public function __construct(PaymentRepositoryInterface $paymentRepository, FraudeDetectionService $fraudeService)
    {
        $this->paymentRepository = $paymentRepository;
        $this->fraudeService = $fraudeService;
    }

    public function processTransaction(array $data): array
    {
        $isFraudulent = $this->fraudeService->isFraudulent($data);

        $status = $isFraudulent ? 'rejeitado': 'aprovado';

        $paymentData = [
            'amount' => $data['amount'],
            'card_number' => Str::substr($data['card_number'], -4), 
            'card_holder' => $data['card_holder'],
            'status' => $status,
        ];

        $payment = !$isFraudulent ? 
            $this->paymentRepository->create($paymentData)
            : $payment = null;
        

        return [
            'message' => $isFraudulent ? 'Transação rejeitada por motivos de segurança' : 'Transação processada com sucesso.',
            'status' => $status,
            'payment_id' => $payment?->id
        ];
        }
    }
