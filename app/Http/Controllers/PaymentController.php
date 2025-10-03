<?php
namespace App\Http\Controllers;
use App\Services\PaymentService;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    protected $paymentService;
    
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
        return response()->json($result, 200);
    }
}
