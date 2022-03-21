<?php

namespace App\Encryption;

interface EncryptionInterface
{
    public function encrypt(string $value, string $key): string;

    public function decrypt(string $value): string;
}