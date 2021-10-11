<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use App\Security\LoginFormAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;

class UserController extends AbstractController
{
    /**
     * @param UserRepository $userRepository
     * @return Response
     * @Route("/user", name="app_user_index")
     */
    public function index(UserRepository $userRepository): Response
    {
        $users = $userRepository->findAll();

        return $this->render('user/index.html.twig', [
            'users' => $users
        ]);
    }

    /**
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return Response
     * @Route("/user/new", name="app_user_new")
     */
    public function new(Request                     $request,
                        EntityManagerInterface      $entityManager,
                        UserPasswordHasherInterface $hasher,
                        UserAuthenticatorInterface  $authenticator,
                        LoginFormAuthenticator      $loginFormAuthenticator): Response
    {
        $form = $this->createForm(RegistrationFormType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var $newUser User */
            $newUser = $form->getData();

            $plainPassword = $form['plainPassword']->getData();
            $newUser->setPassword($hasher->hashPassword($newUser, $plainPassword));

            $entityManager->persist($newUser);
            $entityManager->flush();

            return $authenticator->authenticateUser(
                $newUser,
                $loginFormAuthenticator,
                $request
            );
        }
        return $this->render('user/user_new.html.twig', [
            'userForm' => $form->createView()
        ]);
    }

    /**
     * @param EntityManagerInterface $entityManager
     * @param Request $request
     * @return Response
     * @Route("/user/create", name="app_user_create_api", methods={"POST"})
     */
    public function create(EntityManagerInterface $entityManager, Request $request): Response
    {
        $user = new User();
        $user->setFirstName($request->request->get('firstName'))
            ->setLastName($request->request->get('lastName'))
            ->setEmail($request->request->get('email'));

        $entityManager->persist($user);
        $entityManager->flush();

        return $this->redirectToRoute('app_user_show', [
            'email' => $user->getEmail()
        ]);
    }

    /**
     * @param User $user
     * @return Response
     * @Route("/user/{email}", name="app_user_show")
     * @IsGranted("USER_VIEW", subject="user")
     */
    public function show(User $user): Response
    {
        return $this->render('user/user_show.html.twig', [
            'user' => $user
        ]);
    }

    /**
     * @param User $user
     * @return Response
     * @Route("/user/{email}/modify", name="app_user_modify")
     */
    public function modify(User $user): Response
    {
        return $this->render('user/user_modify.html.twig', [
            'user' => $user
        ]);
    }

    /**
     * @param User $user
     * @param EntityManagerInterface $entityManager
     * @param Request $request
     * @return Response
     * @Route("/user/{email}/update", name="app_user_update_api", methods={"POST"})
     */
    public function update(User $user, EntityManagerInterface $entityManager, Request $request): Response
    {
        $user->setFirstName($request->request->get('firstName'))
            ->setLastName($request->request->get('lastName'))
            ->setEmail($request->request->get('email'));

        $entityManager->flush();

        return $this->redirectToRoute('app_user_show', [
            'email' => $user->getEmail()
        ]);
    }

    /**
     * @param User $user
     * @param EntityManagerInterface $entityManager
     * @return Response
     * @Route("/user/{email}/delete", name="app_user_delete_api")
     */
    public function delete(User $user, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($user);
        $entityManager->flush();

        return $this->redirectToRoute('app_user_index');
    }
}
