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
  search: (q: string, page = 1, filters?: Record<string, string>) =>
    request<SearchResponse>(
      `/search?q=${encodeURIComponent(q)}&page=${page}${filters ? "&" + new URLSearchParams(filters).toString() : ""}`
    ),

  document: (id: string) => request<DocumentResponse>(`/documents/${id}`),

  dossiers: (page = 1) => request<DossiersResponse>(`/dossiers?page=${page}`),

  dossier: (id: string) => request<DossierResponse>(`/dossiers/${id}`),

  settings: () => request<SiteSettings>("/settings"),

  stats: () => request<StatsResponse>("/stats"),

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
};

// Types
export interface SearchResponse {
  hits: SearchHit[];
  found: number;
  page: number;
  per_page: number;
  total_pages: number;
  search_time_ms: number;
  facets?: Record<string, FacetCount[]>;
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

export interface FacetCount { value: string; count: number; }
export interface DossiersResponse { data: SearchHit[]; total: number; page: number; per_page: number; total_pages: number; }
export interface DossierResponse extends DocumentResponse { members: SearchHit[]; }
export interface SiteSettings { name: string; total_documents: number; }
export interface StatsResponse { total_documents: number; total_enriched: number; latest_sync: string; organisations: any[]; themes: any[]; }
