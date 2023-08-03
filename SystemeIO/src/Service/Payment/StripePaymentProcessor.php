<?php

namespace App\Service\Payment;
class StripePaymentProcessor
{
    /**
     * @return bool true if payment was succeeded, false otherwise
     */
    public function processPayment(float $price): bool
    {
        if ($price < 10) {
            return false;
        }

        //process payment logic
        return true;
    }
}