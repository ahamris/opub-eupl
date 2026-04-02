<?php

namespace App\Ai\Agents;

use Laravel\Ai\Concerns\RemembersConversations;
use Laravel\Ai\Contracts\Agent;
use Laravel\Ai\Contracts\Conversational;
use Laravel\Ai\Promptable;
use Stringable;

class ChatAgent implements Agent, Conversational
{
    use Promptable, RemembersConversations;

    protected string $searchContext = '';

    public function withSearchContext(string $context): static
    {
        $this->searchContext = $context;

        return $this;
    }

    public function instructions(): Stringable|string
    {
        $base = <<<'INSTRUCTIONS'
Je bent de OPub AI-assistent die vragen beantwoordt over Nederlandse overheidsdocumenten.

REGELS:
- Gebruik ALLEEN informatie uit de zoekresultaten hieronder
- Verzin GEEN informatie die niet in de documenten staat
- Verwijs naar bronnen met nummers: "Volgens document 1..." of "Document 2 vermeldt..."
- Schrijf in begrijpelijk Nederlands (B1-niveau)
- Wees concreet: noem specifieke datums, bedragen, en organisaties
- Als informatie niet beschikbaar is, zeg dat eerlijk
- Houd antwoorden beknopt maar informatief (max 300 woorden)
- Begin altijd met het directe antwoord, geef dan details
INSTRUCTIONS;

        if ($this->searchContext) {
            return $base . "\n\nZOEKRESULTATEN:\n" . $this->searchContext;
        }

        return $base;
    }
}
