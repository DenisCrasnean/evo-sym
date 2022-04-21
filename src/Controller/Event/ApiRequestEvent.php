<?php

namespace App\Controller\Event;

use App\Exception\EncoderNotSupportedException;
use Symfony\Component\HttpFoundation\HeaderUtils;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\EventDispatcher\Event;

class ApiRequestEvent extends Event
{
    public const NAME = 'api.request';


}
