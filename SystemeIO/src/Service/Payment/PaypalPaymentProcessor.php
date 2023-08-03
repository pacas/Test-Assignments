<?php

namespace App\Service\Payment;
use Exception;

class PaypalPaymentProcessor
{
    /**
     * @throws Exception in case of a failed payment
     */
    public function pay(float $price): void
    {
        if ($price > 100) {
            throw new Exception('Too high price');
        }

        //process payment logic
    }
}