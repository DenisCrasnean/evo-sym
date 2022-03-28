<?php

namespace App\Encryption;

class CaesarEncryption implements EncryptionInterface
{
    private string $key;

    public function __construct(string $programmeImportApiEncryptionKey)
    {
        $this->key = $programmeImportApiEncryptionKey;
    }

    public function encrypt(string $value, string $key): string
    {
        if (!ctype_alpha($value)) {
            return $value;
        }

        $offset = ord(ctype_upper($value) ? 'A' : 'a');

        return chr((int) fmod(((ord($value) + intval($key)) - $offset), 26) + $offset);
    }

    public function decrypt(string $value): string
    {
        $output = '';
        $key = 26 - intval($this->key);

        $characters = str_split($value);
        foreach ($characters as $character) {
            $output .= $this->encrypt($character, (string) $key);
        }

        return $output;
    }
}
