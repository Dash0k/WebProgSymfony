<?php

namespace App\Tests;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use App\Service\ArticleService;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

//Проверка того, что вызываются logger, info и getRecentArticles
class ArticleServiceTest extends TestCase
{
    private ArticleRepository $articleRepository;
    private LoggerInterface $logger;

    protected function setUp(): void
    {
        $this->articleRepository = $this->createMock(ArticleRepository::class);
        $this->logger = $this->createMock(LoggerInterface::class);
    }
    /** 
    * @covers ArticleService::getRecentArticles
    * @covers ArticleService::__construct
    */
    public function testRecentArticles(): void
    {
        $service = new ArticleService(
            $this->articleRepository,
            $this->logger,
        );
    

        $this->articleRepository->expects($this->once())->method('getRecentArticles')
            ->with(3)
            ->willReturn([new Article(), new Article(), new Article()]);

        $this->logger->expects($this->once())->method('info');

        $articles = $service->getRecentArticles(3);

        $this->assertTrue(count($articles) === 3);
    }
}
