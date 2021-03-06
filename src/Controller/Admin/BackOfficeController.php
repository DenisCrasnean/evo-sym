<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BackOfficeController extends AbstractController
{
    /**
     * @Route("/admin/dashboard", name="backoffice_dashboard", methods={"GET"})
     */
    public function index(): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        return $this->render('admin/dashboard/index.html.twig', [
            'user' => $this->getUser(),
        ]);
    }
}
