<?php

namespace App\DataTransferObjects\OpenOverheid;

class OpenOverheidSearchResult
{
    public function __construct(
        public readonly array $items,
        public readonly int $total,
        public readonly int $page,
        public readonly int $perPage,
        public readonly bool $hasNextPage,
        public readonly bool $hasPreviousPage,
    ) {}

    public function toArray(): array
    {
        return [
            'items' => $this->items,
            'total' => $this->total,
            'page' => $this->page,
            'perPage' => $this->perPage,
            'hasNextPage' => $this->hasNextPage,
            'hasPreviousPage' => $this->hasPreviousPage,
        ];
    }
}
