<?php

declare(strict_types=1);

namespace App\Command;

class CaesarDecryption
{
    public function cipher(string $character, int $shift): string
    {
        if (!ctype_alpha($character)) {
            return $character;
        }

        $offset = ord(ctype_upper($character) ? 'A' : 'a');

        return chr((int) fmod(((ord($character) + $shift) - $offset), 26) + $offset);
    }

    public function encipher(string $input, int $shift): string
    {
        $output = '';

        $characters = str_split($input);
        foreach ($characters as $character) {
            $output .= $this->cipher($character, $shift);
        }

        return $output;
    }

    public function decipher($input, $shift): string
    {
        return $this->encipher($input, 26 - $shift);
    }
}
