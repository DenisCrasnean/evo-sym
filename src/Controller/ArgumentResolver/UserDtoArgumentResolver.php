<?php

namespace App\Controller\ArgumentResolver;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentResolver;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use App\Controller\Dto\UserDto;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

class UserDtoArgumentResolver implements ArgumentValueResolverInterface
{
    private array $userDto;

    public function supports(Request $request, ArgumentMetadata $argument): bool
    {
        return $argument->getType() === UserDto::class;
    }

    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        $data = $request->getContent();
        $decodedData = json_decode($data, true);
        $userDto = new UserDto();
        $userDto->lastName = $decodedData['lastName'];
        $userDto->firstName = $decodedData['firstName'];
        $userDto->email = $decodedData['email'];
        $userDto->cnp = $decodedData['cnp'];
        $userDto->password = $decodedData['password'];

        yield $userDto;
    }
}