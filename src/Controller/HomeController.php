<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\ArticleServiceInterface;
use App\Dto\SearchDto;
use App\Form\AppSearchType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    private const RECENT_ARTICLE_COUNT_ON_HOME = 5;

    #[Route('/', name: 'homepage')]
    public function index(ArticleServiceInterface $articleService, Request $request): Response
    {
        $searchDto = new SearchDto();

        $form = $this->createForm(AppSearchType::class, $searchDto);
        
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $query = $articleService->getRecentArticles(self::RECENT_ARTICLE_COUNT_ON_HOME, $searchDto->getSearch());
        } else {
            $query = $articleService->getRecentArticles(self::RECENT_ARTICLE_COUNT_ON_HOME);
        }

        return $this->render('home/index.html.twig', [
            'articles' => $query->getResult(),
            'form' => $form,
        ]);
    }
}
