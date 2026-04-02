const BASE = "/api/v2";

function getCsrf(): string {
  return document.querySelector<HTMLMetaElement>('meta[name="csrf-token"]')?.content || "";
}

async function request<T>(path: string, options?: RequestInit): Promise<T> {
  const res = await fetch(`${BASE}${path}`, {
    headers: {
      Accept: "application/json",
      "Content-Type": "application/json",
      "X-CSRF-TOKEN": getCsrf(),
    },
    ...options,
  });

  if (!res.ok) throw new Error(`API ${res.status}: ${res.statusText}`);
  return res.json();
}

/** For session-based routes (chat) that go through web middleware */
async function webRequest<T>(path: string, options?: RequestInit): Promise<T> {
  const res = await fetch(path, {
    headers: {
      Accept: "application/json",
      "Content-Type": "application/json",
      "X-CSRF-TOKEN": getCsrf(),
    },
    credentials: "same-origin",
    ...options,
  });

  if (!res.ok) throw new Error(`API ${res.status}: ${res.statusText}`);
  return res.json();
}

export const api = {
  // Public read endpoints (via /api/v2)
  search: (q: string, page = 1, perPage = 20, filters?: Record<string, string>, options?: { semantic?: boolean; group_by?: string }) => {
    const p = new URLSearchParams();
    p.set("q", q);
    p.set("page", String(page));
    p.set("per_page", String(perPage));
    if (options?.semantic) p.set("semantic", "1");
    if (options?.group_by) p.set("group_by", options.group_by);
    if (filters) {
      for (const [k, v] of Object.entries(filters)) p.set(k, v);
    }
    return request<SearchResponse>(`/search?${p.toString()}`);
  },

  similar: (id: string) => request<SimilarResponse>(`/documents/${encodeURIComponent(id)}/similar`),

  document: (id: string) => request<DocumentResponse>(`/documents/${id}`),

  dossiers: (page = 1) => request<DossiersResponse>(`/dossiers?page=${page}`),

  dossier: (id: string) => request<DossierResponse>(`/dossiers/${id}`),

  settings: () => request<SiteSettings>("/settings"),

  stats: () => request<StatsResponse>("/stats"),

  organisation: (name: string) =>
    request<OrganisationResponse>(`/organisations/${encodeURIComponent(name)}`),

  wooVerzoek: (data: { organisation: string; naam: string; email: string; onderwerp: string; omschrijving: string }) =>
    request<WooVerzoekResponse>("/woo-verzoek", {
      method: "POST",
      body: JSON.stringify(data),
    }),

  // Chat endpoints (via web routes, session-based)
  chatSend: (message: string, conversationId?: string | null) =>
    webRequest<ChatResponse>("/chat/send", {
      method: "POST",
      body: JSON.stringify({ message, conversation_id: conversationId }),
    }),

  chatConversations: () => webRequest<Conversation[]>("/chat/conversations"),

  chatMessages: (id: string) => webRequest<ChatMessage[]>(`/chat/conversations/${id}/messages`),

  chatDelete: (id: string) =>
    webRequest<{ deleted: boolean }>(`/chat/conversations/${id}`, {
      method: "DELETE",
    }),

  /** Stream a chat response via SSE. Returns an async iterable of events. */
  chatStream: (message: string, conversationId?: string | null) => {
    const controller = new AbortController();
    const promise = fetch("/chat/stream", {
      method: "POST",
      headers: {
        Accept: "text/event-stream",
        "Content-Type": "application/json",
        "X-CSRF-TOKEN": getCsrf(),
      },
      credentials: "same-origin",
      body: JSON.stringify({ message, conversation_id: conversationId }),
      signal: controller.signal,
    });

    return {
      abort: () => controller.abort(),
      async *events(): AsyncGenerator<ChatStreamEvent> {
        const res = await promise;
        if (!res.ok || !res.body) throw new Error(`Stream ${res.status}`);
        const reader = res.body.getReader();
        const decoder = new TextDecoder();
        let buffer = "";

        while (true) {
          const { done, value } = await reader.read();
          if (done) break;
          buffer += decoder.decode(value, { stream: true });
          const lines = buffer.split("\n");
          buffer = lines.pop() || "";
          for (const line of lines) {
            if (line.startsWith("data: ")) {
              try {
                yield JSON.parse(line.slice(6)) as ChatStreamEvent;
              } catch {}
            }
          }
        }
      },
    };
  },
};

// Types
export interface SearchGroup {
  group_key: string;
  found: number;
  hits: SearchHit[];
}

export interface SearchResponse {
  hits: SearchHit[];
  found: number;
  page: number;
  per_page: number;
  total_pages: number;
  search_time_ms: number;
  facets?: Record<string, FacetCount[]>;
  groups?: SearchGroup[];
  group_by?: string;
  semantic?: boolean;
}

export interface SearchHit {
  id: string;
  external_id: string;
  title: string;
  description: string;
  organisation: string;
  publication_date: string;
  document_type: string;
  category: string;
  theme: string;
  vector_distance?: number | null;
}

export interface SimilarResponse {
  document: string;
  similar: {
    external_id: string;
    title: string;
    description: string;
    organisation: string;
    publication_date: string;
    theme: string;
    vector_distance: number | null;
  }[];
  search_time_ms: number;
}

export interface DocumentResponse {
  id: number;
  external_id: string;
  title: string;
  ai_enhanced_title: string | null;
  description: string;
  ai_summary: string | null;
  ai_keywords: string[] | null;
  organisation: string;
  publication_date: string;
  document_type: string;
  category: string;
  theme: string;
  metadata: Record<string, any>;
  synced_at: string;
  ai_enhanced_at: string | null;
}

export interface ChatResponse {
  answer: string;
  conversation_id: string;
  sources?: Source[];
}

export interface Source {
  num: number;
  id: string;
  title: string;
  organisation: string;
  date: string;
  url: string;
}

export interface Conversation {
  id: string;
  title: string;
  created_at: string;
  updated_at: string;
}

export interface ChatMessage {
  type: "user" | "ai";
  text: string;
  answer?: string;
  sources?: Source[];
}

export type ChatStreamEvent =
  | { type: "sources"; sources: Source[] }
  | { type: "thinking"; step: string }
  | { type: "token"; text: string }
  | { type: "done"; conversation_id: string | null }
  | { type: "error"; message: string };

export interface FacetCount { value: string; count: number; }
export interface DossiersResponse { data: SearchHit[]; total: number; page: number; per_page: number; total_pages: number; }
export interface DossierResponse extends DocumentResponse { members: SearchHit[]; }
export interface SiteSettings { name: string; total_documents: number; }
export interface StatsResponse {
  total_documents: number;
  total_enriched: number;
  latest_sync: string;
  organisations: { organisation: string; count: number }[];
  themes: { theme: string; count: number }[];
  monthly_publications: { month: string; count: number }[];
  top_categories: { category: string; count: number }[];
}

export interface BestuursorgaanInfo {
  id: number;
  slug: string;
  naam: string;
  afkorting: string | null;
  type: string;
  logo_url: string | null;
  beschrijving: string | null;
  bezoekadres: string | null;
  postadres: string | null;
  woo_adres: string | null;
  woo_email: string | null;
  telefoon: string | null;
  email: string | null;
  website: string | null;
  contactformulier_url: string | null;
  is_woo_plichtig: boolean;
  woo_url: string | null;
  relatie_ministerie: string | null;
  is_claimed: boolean;
}

export interface OrganisationResponse {
  name: string;
  total_documents: number;
  total_enriched: number;
  themes: { theme: string; count: number }[];
  categories: { category: string; count: number }[];
  monthly_publications: { month: string; count: number }[];
  bestuursorgaan: BestuursorgaanInfo | null;
  recent_documents: {
    external_id: string;
    title: string;
    description: string;
    publication_date: string;
    category: string;
    theme: string;
    document_type: string;
  }[];
}

export interface WooVerzoekResponse {
  success: boolean;
  message: string;
  woo_email: string | null;
  contact_id: number;
}
