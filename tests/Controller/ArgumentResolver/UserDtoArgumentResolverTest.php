<?php

namespace App\Tests\Controller\ArgumentResolver;

use App\Controller\Dto\UserDto;
use App\Controller\ArgumentResolver\UserDtoArgumentResolver;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

class UserDtoArgumentResolverTest extends TestCase
{
    private UserDtoArgumentResolver $userDtoArgumentResolver;

    protected function setUp(): void
    {
        parent::setUp();
        $this->userDtoArgumentResolver = new UserDtoArgumentResolver();
    }

    public function testSupportsDtoClass()
    {
        $request = Request::create('/test');
        $argumentMetadata = new ArgumentMetadata('test', UserDto::class, true, true, true, false);
        $result = $this->userDtoArgumentResolver->supports($request, $argumentMetadata);

        self::assertNotFalse($result);
    }

    public function testResolveArgument()
    {
        $payload = [
            'firstName' => 'Maria',
            'lastName' => 'Ion',
            'email' => 'ion.maria@gmail.com',
            'password' => 'admin2342admin',
            'cnp' => '1941020303927',
        ];
        $request = Request::create(
            '/test',
            'GET',
            [],
            [],
            [],
            [],
            json_encode($payload)
        );

        $argumentMetadata = new ArgumentMetadata('test', UserDto::class, true, true, true, false);
        foreach ($this->userDtoArgumentResolver->resolve($request, $argumentMetadata) as $result) {
            $dto = $result;
        }

        $userDto = new UserDto();
        $userDto->lastName = 'Ion';
        $userDto->firstName = 'Maria';
        $userDto->email = 'ion.maria@gmail.com';
        $userDto->password = 'admin2342admin';
        $userDto->cnp = '1941020303927';

        self::assertEquals($userDto, $dto);
    }
}
