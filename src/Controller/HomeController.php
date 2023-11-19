<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    private const RECENT_ARTICLE_COUNT_ON_HOME = 5;

    #[Route('/', name: 'homepage')]
    public function index(ArticleRepository $articleRepository): Response
    {

        return $this->render('home/index.html.twig', [
            'articles' => $articleRepository->getRecentArticles(self::RECENT_ARTICLE_COUNT_ON_HOME),
        ]);
    }
}
