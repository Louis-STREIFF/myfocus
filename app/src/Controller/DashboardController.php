<?php

namespace App\Controller;

use App\Service\NewsService;
use App\Service\WeatherService;
use App\Entity\Objective;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\PreferenceType;

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

        // NEWS
        $keywords = $user->getFavoriteKeywords();
        $news = $newsService->getNewsForKeywords($keywords);

        // MÉTÉO
        $city = $user->getCity();
        $weather = null;

        if (!empty($city) && trim($city) !== '') {
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
