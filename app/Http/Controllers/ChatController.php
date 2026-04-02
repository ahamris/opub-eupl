<?php

namespace App\Http\Controllers;

use App\Ai\Agents\ChatAgent;
use App\Services\Typesense\TypesenseSearchService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Laravel\Ai\Enums\Lab;

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
                $date = isset($doc['publication_date']) && $doc['publication_date'] > 0
                    ? date('d-m-Y', $doc['publication_date']) : '';

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
