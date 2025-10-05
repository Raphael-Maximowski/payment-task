<?php
namespace App\Http\Controllers;
use App\Services\PaymentService;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    protected $paymentService; // Injeção de dependência do serviço de pagamento
    
    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    public function store(Request $request)
    {

        $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'card_number' => 'required|string',
            'card_holder' => 'required|string',
        ]);

        $result = $this->paymentService->processTransaction($request->all());
        $status_code = $result['status'] == 'aprovado' ? 201 : 401;
        return response()->json($result, $status_code);
    }
}
