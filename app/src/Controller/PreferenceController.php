<?php

namespace App\Controller;

use App\Form\PreferenceType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/preference', name: 'app_preference_')]
class PreferenceController extends AbstractController
{
    #[Route('/edit', name: 'edit')]
    public function edit(
        Request $request,
        Security $security,
        EntityManagerInterface $entityManager
    ): Response {
        /** @var \App\Entity\User $user */
        $user = $security->getUser();

        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        // Le form type doit exister dans src/Form/PreferenceType.php
        $form = $this->createForm(PreferenceType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Les champs city / favoriteKeywords sont mappés sur User
            $entityManager->flush();

            return $this->redirectToRoute('app_dashboard');
        }

        return $this->render('preference/edit.html.twig', [
            'form' => $form->createView(),
            'user' => $user,
        ]);
    }
}
