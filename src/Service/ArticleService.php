<?php

namespace App\Service;

use App\Repository\ArticleRepository;
use Psr\Log\LoggerInterface;

class ArticleService implements ArticleServiceInterface
{
    public function __construct(
        private readonly ArticleRepository $articleRepository,
        private readonly LoggerInterface $logger,
        )
    {

    }

    public function getRecentArticles(int $count)
    {
        $this->logger->info(sprintf('getting %d recent articles', $count));
        return $this->articleRepository->getRecentArticles($count);
    }
}