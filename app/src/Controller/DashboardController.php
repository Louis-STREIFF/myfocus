<?php

namespace App\Controller;

use App\Service\NewsService;
use App\Service\WeatherService;
use App\Entity\Objective;

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
        $objectives = $objectiveRepository->getDashboardObjectives($user);
        
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
            'objectives' => $objectives,
            'weather'  => $weather,
        ]);
    }

    
}
