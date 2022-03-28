<?php

namespace App\Controller;

use App\Controller\Dto\DtoInterface;
use App\Controller\Filter\ProgrammeFilter;
use App\Repository\ProgrammeRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route(path="/api/programmes")
 **/
class ProgrammeController
{
    private DtoInterface $programmeDto;

    private ProgrammeRepository $programmeRepository;

    private ProgrammeFilter $programmeFilter;

    private SerializerInterface $serializer;

    private ValidatorInterface $validator;

    public function __construct(
        DtoInterface $programmeDto,
        ProgrammeRepository $programmeRepository,
        ValidatorInterface $validator,
        SerializerInterface $serializer,
        ProgrammeFilter $programmeFilter
    ) {
        $this->programmeDto = $programmeDto;
        $this->validator = $validator;
        $this->programmeRepository = $programmeRepository;
        $this->serializer = $serializer;
        $this->programmeFilter = $programmeFilter;
    }

    /**
     * @Route(methods={"GET"})
     **/
    public function fetchAll(Request $request, AuthenticationUtils $authenticationUtils): Response
    {
        $error = $authenticationUtils->getLastAuthenticationError();

        $programmes = $this->programmeRepository
            ->findAll($this->programmeFilter->handle($request));

        $serializedProgrammes = $this->serializer->serialize($programmes, 'json', [
            'groups' => 'app:programme:fetchAll'
        ]);

        return new JsonResponse($serializedProgrammes, 200, [], true);
    }
}
