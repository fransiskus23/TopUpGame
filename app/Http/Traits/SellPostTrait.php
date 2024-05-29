<?php

namespace App\Http\Traits;

trait SellPostTrait
{
    protected function getValueByStatus($status)
    {
        $array = [
            'pending' => 0,
            'approval' => 1,
            'resubmission' => 2,
            'hold' => 3,
            'soft-reject' => 4,
            'hard-reject' => 5,
        ];
        return $array[$status] ?? null;
    }
}
