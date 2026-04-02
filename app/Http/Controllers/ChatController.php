<?php

namespace App\Http\Controllers;

use App\Ai\Agents\ChatAgent;
use App\Services\Typesense\TypesenseSearchService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Laravel\Ai\Enums\Lab;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ChatController extends Controller
{
    public function index()
    {
        return view('chat');
    }

    /**
     * Send a message and get an AI response.
     */
    public function send(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'message' => ['required', 'string', 'max:1000'],
            'conversation_id' => ['nullable', 'string'],
        ]);

        $message = $validated['message'];
        $conversationId = $validated['conversation_id'] ?? null;

        // 1. Search relevant documents
        [$searchContext, $sources] = $this->buildSearchContext($message);

        // 2. Try Laravel AI SDK agent
        try {
            $agent = (new ChatAgent)->withSearchContext($searchContext);

            if ($conversationId) {
                $agent = $agent->continue($conversationId, as: $request->user() ?? (object) ['id' => $this->getUserId($request)]);
            } else {
                $agent = $agent->forUser($request->user() ?? (object) ['id' => $this->getUserId($request)]);
            }

            $response = $agent->prompt(
                $message,
                provider: Lab::Ollama,
                model: config('ollama.model', 'bramvanroy/geitje-7b-ultra:Q4_K_M'),
                timeout: 120,
            );

            $newConversationId = $response->conversationId ?? $conversationId;

            if (! $conversationId && $newConversationId) {
                $title = mb_substr($message, 0, 80);
                DB::table('agent_conversations')
                    ->where('id', $newConversationId)
                    ->update(['title' => $title . (mb_strlen($message) > 80 ? '...' : '')]);
            }

            return response()->json([
                'answer' => (string) $response,
                'conversation_id' => $newConversationId,
                'sources' => $sources,
            ]);
        } catch (\Exception $e) {
            Log::error('Chat agent error, using fallback', ['error' => $e->getMessage()]);

            // 3. Fallback: direct Ollama call
            return $this->fallbackResponse($message, $sources, $conversationId);
        }
    }

    /**
     * Stream a chat response via SSE (Server-Sent Events).
     */
    public function stream(Request $request): StreamedResponse
    {
        $validated = $request->validate([
            'message' => ['required', 'string', 'max:1000'],
            'conversation_id' => ['nullable', 'string'],
        ]);

        $message = $validated['message'];
        $conversationId = $validated['conversation_id'] ?? null;

        return response()->stream(function () use ($message, $conversationId, $request) {
            // 1. Search relevant documents
            [$searchContext, $sources] = $this->buildSearchContext($message);

            // Send sources immediately
            $this->sendSSE('sources', ['sources' => $sources]);

            // Send thinking step
            $this->sendSSE('thinking', ['step' => 'Antwoord genereren...']);

            // 2. Build prompt
            $systemPrompt = <<<'PROMPT'
Je bent de oPub AI-assistent die vragen beantwoordt over Nederlandse overheidsdocumenten.

REGELS:
- Gebruik ALLEEN informatie uit de zoekresultaten hieronder
- Verzin GEEN informatie die niet in de documenten staat
- Verwijs naar bronnen met nummers: [1], [2], etc.
- Schrijf in begrijpelijk Nederlands (B1-niveau)
- Wees concreet: noem specifieke datums, bedragen, en organisaties
- Als informatie niet beschikbaar is, zeg dat eerlijk
- Houd antwoorden beknopt maar informatief (max 300 woorden)
- Begin altijd met het directe antwoord, geef dan details
- Eindig met 2-3 vervolgvragen die de gebruiker zou kunnen stellen, elk op een nieuwe regel voorafgegaan door "→ "
PROMPT;

            $userPrompt = "Vraag: {$message}\n\nZOEKRESULTATEN:\n{$searchContext}\n\nGeef een concreet antwoord.";

            // 3. Stream from Ollama
            $ollamaUrl = rtrim(config('ollama.base_url', 'http://45.140.140.31:11434'), '/');
            $model = config('ollama.model', 'bramvanroy/geitje-7b-ultra:Q4_K_M');

            $fullAnswer = '';

            try {
                $ch = curl_init("{$ollamaUrl}/api/generate");
                curl_setopt_array($ch, [
                    CURLOPT_POST => true,
                    CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
                    CURLOPT_POSTFIELDS => json_encode([
                        'model' => $model,
                        'system' => $systemPrompt,
                        'prompt' => $userPrompt,
                        'stream' => true,
                        'options' => [
                            'temperature' => 0.7,
                            'num_predict' => 1024,
                        ],
                    ]),
                    CURLOPT_TIMEOUT => 120,
                    CURLOPT_WRITEFUNCTION => function ($ch, $data) use (&$fullAnswer) {
                        foreach (explode("\n", trim($data)) as $line) {
                            if (empty($line)) continue;
                            $json = json_decode($line, true);
                            if ($json && isset($json['response'])) {
                                $fullAnswer .= $json['response'];
                                $this->sendSSE('token', ['text' => $json['response']]);
                            }
                        }
                        return strlen($data);
                    },
                ]);
                curl_exec($ch);
                $error = curl_error($ch);
                curl_close($ch);

                if ($error) {
                    Log::error('Ollama streaming error', ['error' => $error]);
                    if (empty($fullAnswer)) {
                        $this->sendSSE('token', ['text' => 'Er is een fout opgetreden. Probeer het opnieuw.']);
                    }
                }
            } catch (\Exception $e) {
                Log::error('Ollama streaming exception', ['error' => $e->getMessage()]);
                if (empty($fullAnswer)) {
                    $this->sendSSE('token', ['text' => 'Er is een fout opgetreden. Probeer het opnieuw.']);
                }
            }

            // 4. Save conversation
            $savedConvoId = $conversationId;
            try {
                if ($fullAnswer) {
                    $userId = $this->getUserId($request);

                    if (! $conversationId) {
                        $savedConvoId = (string) \Illuminate\Support\Str::uuid();
                        $title = mb_substr($message, 0, 80) . (mb_strlen($message) > 80 ? '...' : '');
                        DB::table('agent_conversations')->insert([
                            'id' => $savedConvoId,
                            'user_id' => $userId,
                            'title' => $title,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    } else {
                        DB::table('agent_conversations')
                            ->where('id', $conversationId)
                            ->update(['updated_at' => now()]);
                    }

                    // Save user message
                    DB::table('agent_conversation_messages')->insert([
                        'id' => (string) \Illuminate\Support\Str::uuid(),
                        'conversation_id' => $savedConvoId,
                        'role' => 'user',
                        'content' => $message,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);

                    // Save AI response
                    DB::table('agent_conversation_messages')->insert([
                        'id' => (string) \Illuminate\Support\Str::uuid(),
                        'conversation_id' => $savedConvoId,
                        'role' => 'assistant',
                        'content' => $fullAnswer,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            } catch (\Exception $e) {
                Log::warning('Failed to save conversation', ['error' => $e->getMessage()]);
            }

            // 5. Send done event
            $this->sendSSE('done', ['conversation_id' => $savedConvoId]);
        }, 200, [
            'Content-Type' => 'text/event-stream',
            'Cache-Control' => 'no-cache',
            'Connection' => 'keep-alive',
            'X-Accel-Buffering' => 'no',
        ]);
    }

    /**
     * Send a Server-Sent Event.
     */
    protected function sendSSE(string $type, array $data): void
    {
        echo 'data: ' . json_encode(array_merge(['type' => $type], $data)) . "\n\n";
        if (ob_get_level()) ob_flush();
        flush();
    }

    /**
     * Build search context from Typesense.
     */
    /**
     * @return array{0: string, 1: array} [context, sources]
     */
    protected function buildSearchContext(string $query): array
    {
        try {
            $searchService = app(TypesenseSearchService::class);
            $results = $searchService->search($query, ['per_page' => 5]);

            if (empty($results['hits'])) {
                return ['Geen documenten gevonden.', []];
            }

            $context = "Gevonden: {$results['found']} documenten.\n\n";
            $sources = [];

            foreach ($results['hits'] as $i => $hit) {
                $doc = $hit['document'] ?? $hit;
                $num = $i + 1;
                $externalId = $doc['external_id'] ?? $doc['id'] ?? '';
                $title = $doc['title'] ?? 'Geen titel';
                $desc = mb_substr($doc['description'] ?? '', 0, 300);
                $org = $doc['organisation'] ?? '';
                $pubDate = $doc['publication_date'] ?? 0;
                if (is_numeric($pubDate) && $pubDate > 0) {
                    $date = date('d-m-Y', (int) $pubDate);
                } elseif (is_string($pubDate) && !empty($pubDate) && $pubDate !== '0') {
                    $date = $pubDate;
                } elseif (!empty($doc['synced_at'])) {
                    $date = date('d-m-Y', strtotime($doc['synced_at']));
                } else {
                    $date = date('d-m-Y');
                }

                $context .= "Document {$num}:\n";
                $context .= "Titel: {$title}\n";
                if ($org) $context .= "Organisatie: {$org}\n";
                if ($date) $context .= "Datum: {$date}\n";
                if ($desc) $context .= "Omschrijving: {$desc}\n";
                $context .= "\n";

                $sources[] = [
                    'num' => $num,
                    'id' => $externalId,
                    'title' => $title,
                    'organisation' => $org,
                    'date' => $date,
                    'url' => '/open-overheid/documents/' . $externalId,
                ];
            }

            return [$context, $sources];
        } catch (\Exception $e) {
            Log::warning('Search failed', ['error' => $e->getMessage()]);
            return ['Zoekfunctie is momenteel niet beschikbaar.', []];
        }
    }

    /**
     * Fallback: direct Ollama HTTP call.
     */
    protected function fallbackResponse(string $message, array $sources, ?string $conversationId): JsonResponse
    {
        try {
            $ollamaService = app(\App\Services\AI\OllamaService::class);
            $searchService = app(TypesenseSearchService::class);

            $results = $searchService->search($message, ['per_page' => 5]);
            $docs = array_map(fn($h) => $h['document'] ?? $h, $results['hits'] ?? []);
            $aiResponse = $ollamaService->answerQuestion($message, $docs);

            return response()->json([
                'answer' => $aiResponse['answer'] ?? 'Geen antwoord beschikbaar.',
                'conversation_id' => $conversationId,
                'sources' => $sources,
            ]);
        } catch (\Exception $e) {
            Log::error('Fallback also failed', ['error' => $e->getMessage()]);

            return response()->json([
                'answer' => 'Er is een fout opgetreden bij het verwerken van je vraag. Probeer het later opnieuw.',
                'conversation_id' => $conversationId,
                'sources' => $sources,
            ], 500);
        }
    }

    /**
     * List conversations.
     */
    public function conversations(Request $request): JsonResponse
    {
        $userId = $this->getUserId($request);

        $conversations = DB::table('agent_conversations')
            ->where('user_id', $userId)
            ->orderByDesc('updated_at')
            ->limit(50)
            ->get(['id', 'title', 'created_at', 'updated_at'])
            ->map(fn($c) => [
                'id' => $c->id,
                'title' => $c->title ?: 'Nieuw gesprek',
                'created_at' => $c->created_at,
                'updated_at' => $c->updated_at,
            ]);

        return response()->json($conversations);
    }

    /**
     * Delete a conversation.
     */
    public function deleteConversation(Request $request, string $id): JsonResponse
    {
        $userId = $this->getUserId($request);

        DB::table('agent_conversation_messages')->where('conversation_id', $id)->delete();
        $deleted = DB::table('agent_conversations')
            ->where('id', $id)
            ->where('user_id', $userId)
            ->delete();

        return response()->json(['deleted' => $deleted > 0]);
    }

    /**
     * Load messages for a conversation.
     */
    public function messages(Request $request, string $id): JsonResponse
    {
        $userId = $this->getUserId($request);

        $conversation = DB::table('agent_conversations')
            ->where('id', $id)
            ->where('user_id', $userId)
            ->first();

        if (! $conversation) {
            return response()->json([], 404);
        }

        $messages = DB::table('agent_conversation_messages')
            ->where('conversation_id', $id)
            ->orderBy('created_at')
            ->get(['role', 'content']);

        $formatted = [];
        foreach ($messages as $msg) {
            if ($msg->role === 'user') {
                $formatted[] = ['type' => 'user', 'text' => $msg->content];
            } elseif ($msg->role === 'assistant') {
                $formatted[] = ['type' => 'ai', 'text' => '', 'answer' => $msg->content];
            }
        }

        return response()->json($formatted);
    }

    protected function getUserId(Request $request): int|string
    {
        if ($request->user()) {
            return $request->user()->id;
        }

        return crc32($request->session()->getId());
    }
}
