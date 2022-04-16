<?php

namespace App\Controller\Admin;

use App\Controller\Dto\UserDto;
use App\Form\UserType;
use App\Repository\ProgrammeRepository;
use App\Repository\UserRepository;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\ORMInvalidArgumentException;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;

class UsersController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    private UserRepository $userRepository;

    private LoggerInterface $userLogger;

    public function __construct(
        EntityManagerInterface $entityManager,
        UserRepository $userRepository,
        LoggerInterface $userLogger
    ) {
        $this->entityManager = $entityManager;
        $this->userRepository = $userRepository;
        $this->userLogger = $userLogger;
    }

    /**
     * @Route("/admin/users", name="app_backoffice_users", methods={"GET", "POST"})
     */
    public function index(): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $users = $this->userRepository->findAll();

        return $this->render('admin/dashboard/users.html.twig', [
            'user' => $this->getUser(),
            'users' => $users,
        ]);
    }

    /**
     * @Route("/admin/users/store", name="app_backoffice_users_store", methods={"GET", "POST"})
     */
    public function storeAction(Request $request, UserPasswordHasherInterface $passwordHasher): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $form = $this->createForm(UserType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $form->getData()->setPassword(
                    $passwordHasher
                    ->hashPassword($form->getData(), $form->getData()->getPlainPassword())
                );

            try {
                $this->entityManager->persist($form->getData());
                $this->entityManager->flush();

                $this->addFlash(
                    'success',
                    'User with '.$form->getData()->getEmail().' e-mail added successfully!'
                );

                $this->userLogger->info('User created successfully!', [
                    'user' => $form->getData(),
                    'admin' => $this->getUser()->getUserIdentifier(),
                ]);
            } catch (UniqueConstraintViolationException $e) {
                $this->addFlash(
                    'error',
                    'User with '.$form->getData()->getEmail().' e-mail address already exists!'
                );

                $this->userLogger->error('An error occurred while trying to create a user!', [
                    'exception' => $e,
                    'message' => $e->getMessage(),
                ]);
            }

            return $this->redirectToRoute('app_backoffice_users', [], Response::HTTP_CREATED);
        }

        return $this->renderForm('admin/dashboard/users-form.html.twig', [
            'user' => $this->getUser(),
            'userForm' => $form,
        ]);
    }

    /**
     * @Route("/admin/users/{id}", name="app_backoffice_users_update", methods={"GET", "PUT", "PATCH"})
     */
    public function updateAction(Request $request, $id): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $user = $this->userRepository->findOneBy(['email' => $id]);

        $form = $this->createForm(
            UserType::class,
            $user,
            [
                'method' => 'PUT',
            ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($user);
            $this->entityManager->flush();

            $this->addFlash(
                'success',
                'Your changes for the user with e-mail '.$user->getEmail().' were successfully saved!'
            );

            return $this->redirectToRoute('app_backoffice_users');
        }

        return $this->renderForm('admin/dashboard/users-form.html.twig', [
            'user' => $this->getUser(),
            'userForm' => $form,
        ]);
    }

    /**
     * @Route("/admin/users/del/{id}", name="app_backoffice_users_soft_delete", methods={"DELETE"})
     */
    public function softDeleteAction($id): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        try {
            $user = $this->userRepository->findOneBy(['email' => $id]);
            $this->entityManager->remove($user);
            $this->entityManager->flush();

            $this->addFlash(
                'success',
                'User deleted successfully!'
            );
        } catch (ORMInvalidArgumentException $e) {
            $this->addFlash(
                'error',
                "User doesn't exist or was already deleted!"
            );
        }

        return $this->redirectToRoute('app_backoffice_users');
    }

    /**
     * @Route("/admin/users/recover/{id}", name="app_backoffice_users_recover", methods={"DELETE"})
     */
    public function recoverSoftDeletedUserAction($id): Response
    {
        try {
            $this->entityManager->getFilters()->disable('softdeleteable');
            $user = $this->userRepository->findOneBy(['email' => $id]);
            $this->entityManager->remove($user);
            $this->entityManager->flush();

            $this->addFlash(
                'success',
                'User recovered successfully!'
            );
        } catch (\Exception $e) {
            $this->addFlash(
                'error',
                "User doesn't exist or was deleted from the database!"
            );
        }

        return $this->render('admin/dashboard/users.html.twig');
    }
}
