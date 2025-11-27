<?php

namespace App\Controller;

use App\Service\NewsService;
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
        Security $security
    ): Response {
        /** @var \App\Entity\User $user */
        $user = $security->getUser();

        $keywords = $user->getFavoriteKeywords();
        $news = $newsService->getNewsForKeywords($keywords);

        return $this->render('dashboard/index.html.twig', [
            'user'     => $user,
            'news'     => $news,
            'keywords' => $keywords,
        ]);
    }
}
