<?php

namespace App\Services\CryptoTesting;

class AvalancheEffectService
{
    public function calculate(string $c1, string $c2): float
    {
        $bin1 = '';
        $bin2 = '';

        for ($i = 0; $i < strlen($c1); $i++) {
            $bin1 .= str_pad(decbin(ord($c1[$i])), 8, '0', STR_PAD_LEFT);
            $bin2 .= str_pad(decbin(ord($c2[$i])), 8, '0', STR_PAD_LEFT);
        }

        $diff = 0;
        for ($i = 0; $i < strlen($bin1); $i++) {
            if ($bin1[$i] !== $bin2[$i]) {
                $diff++;
            }
        }

        return ($diff / strlen($bin1)) * 100;
    }
}
