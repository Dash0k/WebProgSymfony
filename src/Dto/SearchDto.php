<?php

declare(strict_types=1);

namespace App\Dto;

class SearchDto
{
    private ?string $search = null;

    public function getSearch(): ?string
    {
        return $this->search;
    }

    public function setSearch(?string $search): void
    {
        $this->search = $search;
    }
}