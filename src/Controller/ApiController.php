<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Article;
use App\Repository\UserRepository;
use App\Service\ArticleServiceInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api', name: 'api.')]

class ApiController extends AbstractController
{
    private const DEFAULT_COUNT = 30;

    #[Route('/recent-articles', methods:['GET'])]
    public function getArticles(Request $request, ArticleServiceInterface $arcticleService, int $count = self::DEFAULT_COUNT): JsonResponse
    {
        if (!($request->query->has('count') && ctype_digit($request->query->get('count')))) {
            $count = self::DEFAULT_COUNT;
        }

        $data = [];

        /** @var Article $article */
        foreach ($arcticleService->getRecentArticles($count)->getQuery()->getResult() as $article) {
            $data[] = [
                'id' => $article->getId(),
                'title' => $article->getTitle(),
                'bode' => $article->getBody(),
                'created_at' => $article->getCreatedAt()->format('d.m.Y H:i:s'),
                'author' => [
                    'id' => $article->getAuthor()->getId(),
                    'name' => $article->getAuthor()->__toString(),
                ],
            ];
        }
        return new JsonResponse($data);
    }

    #[Route('/article/{article}', methods:['GET'])]
    public function article(Article $article): JsonResponse
    {
        return new JsonResponse([
            'id' => $article->getId(),
            'title' => $article->getTitle(),
            'bode' => $article->getBody(),
            'created_at' => $article->getCreatedAt()->format('d.m.Y H:i:s'),
            'author' => [
                'id' => $article->getAuthor()->getId(),
                'name' => $article->getAuthor()->__toString(),
            ],
        ]);
    }

    #[Route('/article', methods:['POST'])]
    public function createArticle(Request $request, EntityManagerInterface $entityManager, UserRepository $userRepository): JsonResponse
    {
        $errorTemplate = 'Поле %s не может быть пустым';

        $errors = [];

        $payload = $request->getContent();

        $data = @json_decode($payload, true);

        if (!isset($data['title'])) {
            $errors[] = sprintf($errorTemplate, 'title');
        }

        if (!isset($data['body'])) {
            $errors[] = sprintf($errorTemplate, 'body');
        }

        if (!empty($errors)) {
            return new JsonResponse($errors, Response::HTTP_BAD_REQUEST);
        }

        $article = new Article();

        $article->setTitle($data['title'])
            ->setBody($data['body']);

        $entityManager->persist($article);
        $entityManager->flush();

        return new JsonResponse([
            'id' => $article->getId(),
            'title' => $article->getTitle(),
            'body' => $article->getBody(),
            'created_at' => $article->getCreatedAt()->format('d.m.Y H:i:s'),
            'author' => [
                'id' => $article->getAuthor()->getId(),
                'name' => $article->getAuthor()->__toString(),
            ],
        ], 201);
    }
}