<?php

namespace App\Service\Payment;
use Exception;

class PaymentService
{
    public function __construct(
        private StripePaymentProcessor $stripePaymentProcessor,
        private PaypalPaymentProcessor $paypalPaymentProcessor
    )
    {
    }

    public function payWith(string $paymentSystem, float $sum): array
    {
        // я поменял в PaymentProcessorах int на float, потому что в результате операций могут быть получены дробные цены
        // вообще отрабатывать ошибки стоило бы в самих процессорах оплаты, как и унифицировать их выходные данные,
        // но я решил не трогать процессоры и оставить их как есть (что несколько мешает сделать лучше)
        try {
            $output = match ($paymentSystem) {
                'paypal' => $this->paypalPaymentProcessor->pay($sum) == null ?
                    ['success' => true, 'error' => null] : null,
                'stripe' => $this->stripePaymentProcessor->processPayment($sum) ?
                    ['success' => true, 'error' => null] : ['success' => false, 'error' => 'Too low price for stripe payment'],
                default => ['success' => false, 'error' => 'Invalid payment system']
            };
        } catch (Exception $exception) {
            $output = ['success' => false, 'error' => 'An error occurred during payment processing'];
        }
        return $output;

    }
}