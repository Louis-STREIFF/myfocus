<?php

// src/Controller/PreferenceController.php
namespace App\Controller;

use App\Form\PreferenceType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PreferenceController extends AbstractController
{
    #[Route('/preference', name: 'app_preference', methods: ['GET'])]
    public function show(Request $request): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(PreferenceType::class, $user);

        return $this->render('preference/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/preference', name: 'app_preference_update', methods: ['POST'])]
    public function update(Request $request, EntityManagerInterface $em): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(PreferenceType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($user);
            $em->flush();

            $this->addFlash('success', 'Vos préférences ont été mises à jour');
            return $this->redirectToRoute('app_preference');
        }

        return $this->render('preference/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}