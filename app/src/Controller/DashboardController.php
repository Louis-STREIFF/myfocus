<?php

namespace App\Controller;

use App\Service\NewsService;
use App\Service\WeatherService;
use App\Entity\Objectives;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_USER')]
class DashboardController extends AbstractController
{
    #[Route('/dashboard', name: 'app_dashboard')]
    public function index(
        NewsService $newsService,
        WeatherService $weatherService,
        Security $security
    ): Response {
        /** @var \App\Entity\User $user */
        $user = $security->getUser();

        $keywords = $user->getFavoriteKeywords();
        $news = $newsService->getNewsForKeywords($keywords);
        $city = $user->getCity();
        if (empty(trim($city ?? ''))) {
            $wearther = new JsonResponse([], Response::HTTP_NO_CONTENT);
        }else{
            $weather = $weatherService->getWeatherByCity($city);
        }
           
        return $this->render('dashboard/index.html.twig', [
            'user'     => $user,
            'news'     => $news,
            'keywords' => $keywords,
            'city'     => $city,
            'weather'  => $weather,
        ]);
    }

    #[Route('/preference/edit', name: 'app_preference_edit')]
    public function edit(Request $request, Security $security, EntityManagerInterface $entityManager): Response {
        /** @var \App\Entity\User $user */

        $user = $security->getUser();

        $form = $this->createForm(PreferenceType::class, $user);
        $form->handleRequest($request);
        $edit = $request->request->has('validation');

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setCity($form->get('city')->getData());
            $user->setFavoriteKeywords($form->get('keywords')->getData());

            /** loading user in database */
            $entityManager->persist($user);
            $entityManager->flush();

            /** loading objectives in database */


            return $this->render('preference/index.html.twig', [
                'user'     => $user,

            ]);
        }

        return $this->render('preference/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);

    }
}
