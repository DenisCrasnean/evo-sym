<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CarController
{
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
