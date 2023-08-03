<?php

namespace App\Entity;

class Coupon
{
    private string $type;
    private float $percent;
    private bool $valid = true;
    // есть пример купона D15, пусть D = динамический, F - статический

    // при более сложных купонах логику можно разнести по местам, сейчас не требуется
    public function __construct(string $coupon) {
        $this->type = substr($coupon, 0, 1);
        // проверка типа
        if ($this->type !== 'D' && $this->type !== 'F') {
            $this->valid = false;
        }
        $percent = substr($coupon, 1);
        // проверка валидности суммы скидки
        is_numeric($percent) && (float) $percent > 0 ? $this->percent = (float) $percent : $this->valid = false;
        if ($this->type === 'D' && $percent > 100) {
            $this->valid = false;
        }
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getPercent(): float
    {
        return $this->percent;
    }

    public function isValid(): bool
    {
        return $this->valid;
    }
}