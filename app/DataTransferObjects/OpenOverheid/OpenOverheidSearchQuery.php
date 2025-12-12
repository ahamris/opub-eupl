<?php

namespace App\DataTransferObjects\OpenOverheid;

class OpenOverheidSearchQuery
{
    public function __construct(
        public readonly ?string $zoektekst = '',
        public readonly int $page = 1,
        public readonly int $perPage = 20, // 10, 20, 50
        public readonly ?string $publicatiedatumVan = null,
        public readonly ?string $publicatiedatumTot = null,
        public readonly string|array|null $documentsoort = null,
        public readonly ?string $informatiecategorie = null,
        public readonly string|array|null $thema = null,
        public readonly string|array|null $organisatie = null,
        public readonly ?string $sort = 'relevance', // relevance, publication_date, modified_date
        public readonly bool $titlesOnly = false,
    ) {}

    public function offset(): int
    {
        return ($this->page - 1) * $this->perPage;
    }
}
