<?php

namespace App\Controller;

use App\Entity\Programme;
use App\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CarController
{
    private EntityManagerInterface $entityManager;

    /**
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/entities-test", name="entities.test",  methods={"GET","HEAD"})
     */
    public function testEntities(): Response
    {
        $customer = new User();
        $customer->setRoles(new ArrayCollection(['customer', 'user']));

        $programme = new Programme();
        $programme->setStartTime(new \DateTime('now'));
        $programme->setEndTime(new \DateTime('+2 hours'));

        $programme->setCustomers(new ArrayCollection([$customer]));

        $this->entityManager->persist($programme);

        $this->entityManager->flush();

//        $userRepository = $this->entityManager->getRepository(User::class);
//
//
//        $programme = new Programme();
//        $programme->setStartTime(new \DateTime('now'));
//        $programme->setEndTime(new \DateTime('+2 hours'));
//
//        /** @var User $customer */
//        $customer = $userRepository->find(1);
//        $this->entityManager->persist($programme);
//
//        /** @var Programme $programme */
////        $programme->setCustomers($programme->getCustomers()->add($programme));
//
//        $customer->addProgramme($programme);
//
//        var_dump(count($customer->getProgrammes()));
//        var_dump(count($programme->getCustomers()));
//
//        $this->entityManager->flush();

        return new JsonResponse([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/CarController.php',
        ], Response::HTTP_OK, []);
    }

    /**
     * @Route("/message", methods={"GET"})
     */
    public function getMessage(): Response
    {
        return new JsonResponse([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/CarController.php',
        ], Response::HTTP_OK, []);
    }

    /**
     * @Route("/cars", name="cars",  methods={"GET","HEAD"})
     */
    public function index(): Response
    {
        return new JsonResponse([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/CarController.php',
        ], Response::HTTP_OK, []);
    }

    /**
     * @Route("/cars/store", name="cars.store",  methods={"POST"})
     */
    public function store(Request $request): Response
    {
        return new JsonResponse($request->getContent(), Response::HTTP_CREATED, []);
    }
}
