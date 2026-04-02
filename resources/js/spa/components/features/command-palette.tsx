import { useState, useEffect, useRef, useCallback } from "react";
import { useNavigate } from "react-router";
import { SearchLg, Zap, XClose, ArrowRight, Building01, Home01, MessageChatCircle, File06, BarChart01, BookOpen01, Mail01, Folder, Tag01, ChevronRight } from "@untitledui/icons";

interface Suggestion {
  external_id: string;
  title: string;
  organisation?: string;
  category?: string;
  document_type?: string;
  theme?: string;
}

interface MenuItem {
  icon: any;
  label: string;
  desc: string;
  to: string;
  color: string;
}

const MENU_ITEMS: MenuItem[] = [
  { icon: Home01, label: "Home", desc: "Startpagina", to: "/", color: "text-gray-600" },
  { icon: SearchLg, label: "Zoekportaal", desc: "Federatief zoeken in Woo-publicaties", to: "/zoeken", color: "text-blue-600" },
  { icon: MessageChatCircle, label: "AI-chat", desc: "Sovereign AI — Ollama + Geitje", to: "/chat", color: "text-violet-600" },
  { icon: Folder, label: "Thema's & dossiers", desc: "Documenten op thema of dossier", to: "/collecties", color: "text-emerald-600" },
  { icon: BarChart01, label: "Dashboard", desc: "Publicatievolumes en Woo-compliance", to: "/dashboard", color: "text-amber-600" },
  { icon: BookOpen01, label: "Kennisbank", desc: "Woo-uitleg, handleidingen, API-docs", to: "/kennisbank", color: "text-cyan-600" },
  { icon: Mail01, label: "Contact & aansluiten", desc: "Kosteloos aansluiten als bestuursorgaan", to: "/contact", color: "text-rose-600" },
  { icon: File06, label: "API Documentatie", desc: "Open API onder EUPL 1.2 (Swagger)", to: "/api/docs", color: "text-indigo-600" },
];

const CAT_COLORS: Record<string, string> = {
  "Wetten en algemeen verbindende voorschriften": "bg-blue-50 text-blue-700",
  "Organisatie en werkwijze": "bg-violet-50 text-violet-700",
  "Bereikbaarheidsgegevens": "bg-cyan-50 text-cyan-700",
  "Jaarplannen en jaarverslagen": "bg-amber-50 text-amber-700",
  "Beschikkingen": "bg-rose-50 text-rose-700",
  "Onderzoeksrapporten": "bg-emerald-50 text-emerald-700",
  "Convenanten": "bg-indigo-50 text-indigo-700",
};

const ORG_COLORS: Record<string, string> = {
  "Tweede Kamer": "bg-blue-50 text-blue-700",
  "Eerste Kamer": "bg-indigo-50 text-indigo-700",
  "Rijkswaterstaat": "bg-cyan-50 text-cyan-700",
  "ministerie van Binnenlandse Zaken en Koninkrijksrelaties": "bg-orange-50 text-orange-700",
  "ministerie van Justitie en Veiligheid": "bg-red-50 text-red-700",
};

function badgeColor(map: Record<string, string>, key: string): string {
  // Try exact match first, then partial
  if (map[key]) return map[key];
  const found = Object.entries(map).find(([k]) => key.toLowerCase().includes(k.toLowerCase()));
  return found ? found[1] : "bg-gray-100 text-gray-600";
}

export function CommandPalette() {
  const [query, setQuery] = useState("");
  const [mode, setMode] = useState<"search" | "ai">("search");
  const [suggestions, setSuggestions] = useState<Suggestion[]>([]);
  const [found, setFound] = useState(0);
  const [searchTime, setSearchTime] = useState(0);
  const [open, setOpen] = useState(false);
  const [activeIdx, setActiveIdx] = useState(-1);
  const navigate = useNavigate();
  const inputRef = useRef<HTMLInputElement>(null);
  const wrapRef = useRef<HTMLDivElement>(null);
  const debounceRef = useRef<ReturnType<typeof setTimeout>>();

  // Cmd+K to open
  useEffect(() => {
    const handler = (e: KeyboardEvent) => {
      if ((e.metaKey || e.ctrlKey) && e.key === "k") {
        e.preventDefault();
        setOpen(true);
        setTimeout(() => inputRef.current?.focus(), 50);
      }
      if (e.key === "Escape") setOpen(false);
    };
    window.addEventListener("keydown", handler);
    return () => window.removeEventListener("keydown", handler);
  }, []);

  // Typesense instant search
  const fetchSuggestions = useCallback((q: string) => {
    if (q.length < 2) { setSuggestions([]); return; }
    clearTimeout(debounceRef.current);
    debounceRef.current = setTimeout(async () => {
      try {
        const res = await fetch(`/api/v2/search?q=${encodeURIComponent(q)}&per_page=6`);
        const data = await res.json();
        setSuggestions(
          (data.hits || []).map((h: any) => ({
            external_id: h.external_id,
            title: h.title,
            organisation: h.organisation,
            category: h.category,
            document_type: h.document_type,
            theme: h.theme,
          }))
        );
        setFound(data.found || 0);
        setSearchTime(data.search_time_ms || 0);
        setActiveIdx(-1);
      } catch {}
    }, 100);
  }, []);

  useEffect(() => { fetchSuggestions(query); }, [query, fetchSuggestions]);

  // Close on outside click
  useEffect(() => {
    const handler = (e: MouseEvent) => {
      if (wrapRef.current && !wrapRef.current.contains(e.target as Node)) setOpen(false);
    };
    document.addEventListener("mousedown", handler);
    return () => document.removeEventListener("mousedown", handler);
  }, []);

  const handleSubmit = (e?: React.FormEvent) => {
    e?.preventDefault();
    if (!query.trim()) return;
    if (mode === "ai") {
      navigate(`/chat?q=${encodeURIComponent(query.trim())}`);
    } else {
      navigate(`/zoeken?q=${encodeURIComponent(query.trim())}`);
    }
    setOpen(false);
  };

  const goTo = (path: string) => { navigate(path); setOpen(false); };

  const filterBy = (key: string, value: string, e: React.MouseEvent) => {
    e.stopPropagation();
    navigate(`/zoeken?q=${encodeURIComponent(query)}&${key}=${encodeURIComponent(value)}`);
    setOpen(false);
  };

  // Filter menu items by query
  const filteredMenu = query.length > 0
    ? MENU_ITEMS.filter((m) => m.label.toLowerCase().includes(query.toLowerCase()) || m.desc.toLowerCase().includes(query.toLowerCase()))
    : [];

  const hasResults = suggestions.length > 0 || filteredMenu.length > 0;
  const showDropdown = open || (query.length >= 2 && hasResults);

  return (
    <div ref={wrapRef} className="relative w-full max-w-2xl mx-auto" style={{ zIndex: 9999, position: "relative" }}>
      {/* Mode toggle */}
      <div className="flex items-center gap-1 mb-3 justify-center">
        <button
          onClick={() => setMode("search")}
          className={`flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-medium transition-all ${
            mode === "search" ? "bg-blue-600 text-white shadow-sm" : "text-gray-500 hover:text-gray-700 hover:bg-white/60"
          }`}
        >
          <SearchLg className="w-3 h-3" /> Zoeken
        </button>
        <button
          onClick={() => { setMode("ai"); inputRef.current?.focus(); }}
          className={`flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-medium transition-all ${
            mode === "ai" ? "bg-gradient-to-r from-blue-600 to-violet-600 text-white shadow-sm" : "text-gray-500 hover:text-gray-700 hover:bg-white/60"
          }`}
        >
          <Zap className="w-3 h-3" /> AI Vraag
        </button>
      </div>

      {/* Input */}
      <form onSubmit={handleSubmit}>
        <div className={`flex items-center gap-3 bg-white border rounded-2xl px-5 py-3 transition-all ${
          showDropdown
            ? "rounded-b-none border-blue-300 ring-4 ring-blue-100/60 shadow-lg"
            : "border-gray-200 shadow-sm focus-within:border-blue-300 focus-within:ring-4 focus-within:ring-blue-100/60 focus-within:shadow-lg"
        }`}>
          {mode === "ai" ? (
            <Zap className="w-5 h-5 text-violet-500 shrink-0" />
          ) : (
            <SearchLg className="w-5 h-5 text-gray-400 shrink-0" />
          )}
          <input
            ref={inputRef}
            type="text"
            value={query}
            onChange={(e) => setQuery(e.target.value)}
            onFocus={() => setOpen(true)}
            onKeyDown={(e) => {
              if (e.key === "Escape") { setOpen(false); return; }
              if (!showDropdown) return;
              const total = filteredMenu.length + suggestions.length;
              if (e.key === "ArrowDown") { e.preventDefault(); setActiveIdx((i) => Math.min(i + 1, total - 1)); }
              else if (e.key === "ArrowUp") { e.preventDefault(); setActiveIdx((i) => Math.max(i - 1, -1)); }
              else if (e.key === "Enter" && activeIdx >= 0) {
                e.preventDefault();
                if (activeIdx < filteredMenu.length) {
                  goTo(filteredMenu[activeIdx].to);
                } else {
                  const sIdx = activeIdx - filteredMenu.length;
                  goTo(`/open-overheid/documents/${suggestions[sIdx].external_id}`);
                }
              }
            }}
            placeholder={mode === "ai" ? "Stel een vraag aan de oPub AI-assistent..." : "Doorzoek 641.000+ Woo-documenten..."}
            className="flex-1 bg-transparent border-0 outline-none text-gray-900 placeholder:text-gray-400 text-base"
            autoComplete="off"
          />
          {query && (
            <button type="button" onClick={() => { setQuery(""); setSuggestions([]); }} className="text-gray-400 hover:text-gray-600">
              <XClose className="w-4 h-4" />
            </button>
          )}
          <div className="hidden sm:flex items-center gap-1.5 text-[10px] text-gray-400 border border-gray-200 rounded-md px-1.5 py-0.5 select-none">
            <kbd>⌘</kbd><kbd>K</kbd>
          </div>
          <button
            type="submit"
            className="px-5 py-2 bg-blue-600 text-white rounded-xl text-sm font-semibold hover:bg-blue-700 transition-colors flex items-center gap-1.5"
          >
            {mode === "ai" ? "Vraag" : "Zoek"}
            <ArrowRight className="w-3.5 h-3.5" />
          </button>
        </div>
      </form>

      {/* Command palette dropdown */}
      {showDropdown && (
        <div
          className="absolute left-0 right-0 bg-white border border-t-0 border-blue-300 rounded-b-2xl overflow-hidden"
          style={{ zIndex: 9999, boxShadow: "0 25px 50px -12px rgba(0,0,0,0.15)" }}
        >
          {/* Menu items (when query matches pages) */}
          {filteredMenu.length > 0 && (
            <div>
              <div className="px-4 py-1.5 text-[10px] font-semibold text-gray-400 uppercase tracking-wider bg-gray-50">
                Pagina's
              </div>
              {filteredMenu.map((m, i) => (
                <button
                  key={m.to}
                  onClick={() => goTo(m.to)}
                  className={`w-full flex items-center gap-3 px-5 py-2.5 text-left transition-colors ${
                    i === activeIdx ? "bg-blue-50" : "hover:bg-gray-50"
                  }`}
                >
                  <m.icon className={`w-4 h-4 ${m.color} shrink-0`} />
                  <div className="flex-1 min-w-0">
                    <span className="text-sm font-medium text-gray-900">{m.label}</span>
                    <span className="text-xs text-gray-400 ml-2">{m.desc}</span>
                  </div>
                  <ChevronRight className="w-3 h-3 text-gray-300" />
                </button>
              ))}
            </div>
          )}

          {/* Search results */}
          {suggestions.length > 0 && mode === "search" && (
            <div>
              <div className="px-4 py-1.5 text-[10px] font-semibold text-gray-400 uppercase tracking-wider bg-gray-50 flex items-center justify-between">
                <span>Documenten</span>
                <span className="normal-case font-normal">
                  <strong className="font-semibold text-gray-500">{found.toLocaleString("nl-NL")}</strong> resultaten · {searchTime}ms
                </span>
              </div>
              {suggestions.map((s, i) => {
                const idx = filteredMenu.length + i;
                return (
                  <button
                    key={s.external_id}
                    onClick={() => goTo(`/open-overheid/documents/${s.external_id}`)}
                    className={`w-full flex items-start gap-3 px-5 py-3 text-left transition-colors ${
                      idx === activeIdx ? "bg-blue-50" : "hover:bg-gray-50"
                    }`}
                  >
                    <File06 className="w-4 h-4 text-gray-400 shrink-0 mt-0.5" />
                    <div className="min-w-0 flex-1">
                      <p className="text-sm text-gray-900 line-clamp-1">{s.title}</p>
                      <div className="flex items-center gap-1.5 mt-1 flex-wrap">
                        {s.organisation && (
                          <span
                            onClick={(e) => filterBy("organisation", s.organisation!, e)}
                            className={`inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-medium cursor-pointer hover:ring-2 hover:ring-blue-200 transition-all ${badgeColor(ORG_COLORS, s.organisation)}`}
                          >
                            <Building01 className="w-2.5 h-2.5" />
                            {s.organisation.length > 35 ? s.organisation.slice(0, 35) + "…" : s.organisation}
                          </span>
                        )}
                        {s.category && (
                          <span
                            onClick={(e) => filterBy("category", s.category!, e)}
                            className={`inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-medium cursor-pointer hover:ring-2 hover:ring-amber-200 transition-all ${badgeColor(CAT_COLORS, s.category)}`}
                          >
                            <Tag01 className="w-2.5 h-2.5" />
                            {s.category.length > 30 ? s.category.slice(0, 30) + "…" : s.category}
                          </span>
                        )}
                      </div>
                    </div>
                  </button>
                );
              })}
            </div>
          )}

          {/* Empty state when no query */}
          {query.length < 2 && open && (
            <div>
              <div className="px-4 py-1.5 text-[10px] font-semibold text-gray-400 uppercase tracking-wider bg-gray-50">
                Navigatie
              </div>
              {MENU_ITEMS.slice(0, 6).map((m) => (
                <button
                  key={m.to}
                  onClick={() => goTo(m.to)}
                  className="w-full flex items-center gap-3 px-5 py-2.5 text-left hover:bg-gray-50 transition-colors"
                >
                  <m.icon className={`w-4 h-4 ${m.color} shrink-0`} />
                  <span className="text-sm text-gray-700">{m.label}</span>
                  <span className="text-xs text-gray-400 ml-auto">{m.desc}</span>
                </button>
              ))}
            </div>
          )}

          {/* All results footer */}
          {suggestions.length > 0 && query.length >= 2 && (
            <button
              onClick={handleSubmit}
              className="w-full flex items-center justify-center gap-2 px-5 py-3 text-xs font-semibold text-blue-600 border-t border-gray-100 hover:bg-blue-50 transition-colors"
            >
              Alle {found.toLocaleString("nl-NL")} resultaten bekijken <ArrowRight className="w-3 h-3" />
            </button>
          )}
        </div>
      )}
    </div>
  );
}
