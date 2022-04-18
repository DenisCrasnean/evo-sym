<?php

namespace App\Controller;

use App\Controller\Filter\ProgrammeFilter;
use App\Repository\ProgrammeRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route(path="/api/programmes")
 **/
class ProgrammeController
{
    private ProgrammeRepository $programmeRepository;

    private ProgrammeFilter $programmeFilter;

    private SerializerInterface $serializer;

    public function __construct(
        ProgrammeRepository $programmeRepository,
        SerializerInterface $serializer,
        ProgrammeFilter $programmeFilter
    ) {
        $this->programmeRepository = $programmeRepository;
        $this->serializer = $serializer;
        $this->programmeFilter = $programmeFilter;
    }

    /**
     * @Route(methods={"GET"})
     **/
    public function fetchAll(Request $request, AuthorizationCheckerInterface $authorizationChecker): Response
    {
        $authorizationChecker->isGranted('ROLE_CUSTOMER');

        $programmes = $this->programmeRepository
            ->findAll($this->programmeFilter->handle($request));

        $serializedProgrammes = $this->serializer->serialize($programmes, 'json', [
            'groups' => 'app:programme:fetchAll',
        ]);

        return new JsonResponse($serializedProgrammes, 200, [], true);
    }
}
