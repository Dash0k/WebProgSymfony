<?php

namespace App\Service;

interface ArticleServiceInterface
{
    public function getRecentArticles(int $count);
}