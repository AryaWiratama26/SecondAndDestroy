<?php

namespace App\Services\CryptoTesting;

use Illuminate\Support\Facades\Config;
use RuntimeException;

class EncryptionTestService
{
    private function getKey(): string
    {
        $key = Config::get('app.key');
        

        if (str_starts_with($key, 'base64:')) {
            $key = base64_decode(substr($key, 7));
        }

        if (strlen($key) !== 32) {
            throw new RuntimeException('Key harus 32 byte.');
        }

        return $key;
    }

    public function encryptForAvalanche(string $plaintext, string $iv): string
    {
        return openssl_encrypt(
            $plaintext,
            'AES-256-CBC',
            $this->getKey(),
            OPENSSL_RAW_DATA,
            $iv
        );
    }
}
