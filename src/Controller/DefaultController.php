<?php

namespace App\Controller;

use App\Form\ChangePasswordAnotherType;
use App\Security\FakeUser;
use App\Form\ChangePasswordType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class DefaultController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(): Response
    {
        return $this->render('default/index.html.twig');
    }

    #[Route('/pwd-change', name: 'pwd_change')]
    public function pwdChangeAction(
        Request                     $request,
        UserPasswordHasherInterface $passwordHasher
    ): Response
    {
        // Fake user jelszó: "test1234"
        $fake = new FakeUser('');
        $hashed = $passwordHasher->hashPassword($fake, 'test1234');
        $user = new FakeUser($hashed);

        $form = $this->createForm(ChangePasswordAnotherType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $currentPassword = $form->get('currentPassword')->getData();

            if (!$passwordHasher->isPasswordValid($user, $currentPassword)) {
                $this->addFlash('error', 'A jelenlegi jelszó nem megfelelő.');
            } else {
                $newPassword = $form->get('plainPassword')->getData();

                $user->setPassword(
                    $passwordHasher->hashPassword($user, $newPassword)
                );

                $this->addFlash('success', 'A jelszavad sikeresen megváltozott (fake user).');

                return $this->redirectToRoute('pwd_change');
            }
        }

        return $this->render('default/pwdca.html.twig', [
            'changePasswordForm' => $form->createView(),
        ]);
    }

    #[Route('/profil', name: 'vezerlopult_profil')]
    public function profilAction(
        Request                     $request,
        UserPasswordHasherInterface $passwordHasher
    ): Response
    {
        // Fake user jelszó: "test1234"
        $fake = new FakeUser('');
        $hashed = $passwordHasher->hashPassword($fake, 'test1234');
        $user = new FakeUser($hashed);

        $passwordForm = $this->createForm(ChangePasswordType::class);
        $passwordForm->handleRequest($request);

        // Jelszó módosítás
        if ($passwordForm->isSubmitted() && $passwordForm->isValid()) {
            $current = $passwordForm->get('currentPassword')->getData();
            $new = $passwordForm->get('newPassword')->getData();
            $confirm = $passwordForm->get('confirmPassword')->getData();

            if (!$passwordHasher->isPasswordValid($user, $current)) {
                $this->addFlash('password_error', 'A jelenlegi jelszó hibás.');
                return $this->redirectToRoute('vezerlopult_profil');
            } elseif ($new !== $confirm) {
                $this->addFlash('password_error', 'Az új jelszó és a megerősítés nem egyezik.');
                return $this->redirectToRoute('vezerlopult_profil');
            } else {
                $user->setPassword($passwordHasher->hashPassword($user, $new));
                $this->addFlash('password_success', 'Jelszó módosítva.');

                return $this->redirectToRoute('vezerlopult_profil');
            }
        }

        return $this->render('default/pwdc.html.twig', [
            'passwordForm' => $passwordForm->createView(),
        ]);
    }
}
