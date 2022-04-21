<?php

namespace App\Controller\Request;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

interface RequestContextInterface
{
    public function getRegex(string $regexPattern): string;

    public function handle(Request $request): Response;
}