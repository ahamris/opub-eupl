import { useState, useEffect, useRef, useCallback } from "react";
import { useNavigate } from "react-router";
import { SearchLg, Zap, XClose, ArrowRight, Building01 } from "@untitledui/icons";

interface Suggestion {
  external_id: string;
  title: string;
  organisation?: string;
  category?: string;
  document_type?: string;
}

const ORG_COLORS: Record<string, string> = {
  "Tweede Kamer": "bg-blue-50 text-blue-700",
  "Eerste Kamer": "bg-indigo-50 text-indigo-700",
  "Rijkswaterstaat": "bg-cyan-50 text-cyan-700",
  "default": "bg-gray-100 text-gray-600",
};

function orgColor(org: string): string {
  return ORG_COLORS[org] || ORG_COLORS["default"];
}

export function SearchBox() {
  const [query, setQuery] = useState("");
  const [mode, setMode] = useState<"search" | "ai">("search");
  const [suggestions, setSuggestions] = useState<Suggestion[]>([]);
  const [found, setFound] = useState(0);
  const [searchTime, setSearchTime] = useState(0);
  const [showSugg, setShowSugg] = useState(false);
  const [activeIdx, setActiveIdx] = useState(-1);
  const navigate = useNavigate();
  const inputRef = useRef<HTMLInputElement>(null);
  const wrapRef = useRef<HTMLDivElement>(null);
  const debounceRef = useRef<ReturnType<typeof setTimeout>>();

  const fetchSuggestions = useCallback((q: string) => {
    if (q.length < 2) { setSuggestions([]); setShowSugg(false); return; }
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
          }))
        );
        setFound(data.found || 0);
        setSearchTime(data.search_time_ms || 0);
        setShowSugg(true);
        setActiveIdx(-1);
      } catch {}
    }, 120);
  }, []);

  useEffect(() => { fetchSuggestions(query); }, [query, fetchSuggestions]);

  useEffect(() => {
    const handler = (e: MouseEvent) => {
      if (wrapRef.current && !wrapRef.current.contains(e.target as Node)) setShowSugg(false);
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
    setShowSugg(false);
  };

  const handleKey = (e: React.KeyboardEvent) => {
    if (!showSugg) return;
    if (e.key === "ArrowDown") {
      e.preventDefault();
      setActiveIdx((i) => Math.min(i + 1, suggestions.length - 1));
    } else if (e.key === "ArrowUp") {
      e.preventDefault();
      setActiveIdx((i) => Math.max(i - 1, -1));
    } else if (e.key === "Enter" && activeIdx >= 0) {
      e.preventDefault();
      navigate(`/open-overheid/documents/${suggestions[activeIdx].external_id}`);
      setShowSugg(false);
    } else if (e.key === "Escape") {
      setShowSugg(false);
    }
  };

  const filterByOrg = (org: string, e: React.MouseEvent) => {
    e.stopPropagation();
    navigate(`/zoeken?q=${encodeURIComponent(query)}&organisation=${encodeURIComponent(org)}`);
    setShowSugg(false);
  };

  return (
    <div ref={wrapRef} className="relative w-full max-w-2xl mx-auto" style={{ zIndex: 100 }}>
      {/* Mode toggle */}
      <div className="flex items-center gap-1 mb-3 justify-center">
        <button
          onClick={() => setMode("search")}
          className={`flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-medium transition-all ${
            mode === "search"
              ? "bg-blue-600 text-white shadow-sm"
              : "text-gray-500 hover:text-gray-700 hover:bg-gray-100"
          }`}
        >
          <SearchLg className="w-3 h-3" /> Zoeken
        </button>
        <button
          onClick={() => { setMode("ai"); inputRef.current?.focus(); }}
          className={`flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-medium transition-all ${
            mode === "ai"
              ? "bg-gradient-to-r from-blue-600 to-violet-600 text-white shadow-sm"
              : "text-gray-500 hover:text-gray-700 hover:bg-gray-100"
          }`}
        >
          <Zap className="w-3 h-3" /> AI Vraag
        </button>
      </div>

      {/* Input */}
      <form onSubmit={handleSubmit}>
        <div className={`flex items-center gap-3 bg-white border rounded-2xl px-5 py-3 shadow-sm transition-all ${
          showSugg && suggestions.length > 0
            ? "rounded-b-none border-blue-300 ring-4 ring-blue-50"
            : "border-gray-200 focus-within:border-blue-300 focus-within:ring-4 focus-within:ring-blue-50"
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
            onFocus={() => suggestions.length > 0 && setShowSugg(true)}
            onKeyDown={handleKey}
            placeholder={mode === "ai" ? "Stel een vraag in gewone taal..." : "Zoek in overheidsdocumenten..."}
            className="flex-1 bg-transparent border-0 outline-none text-gray-900 placeholder:text-gray-400 text-base"
            autoComplete="off"
          />
          {query && (
            <button type="button" onClick={() => { setQuery(""); setSuggestions([]); setShowSugg(false); }} className="text-gray-400 hover:text-gray-600">
              <XClose className="w-4 h-4" />
            </button>
          )}
          <button
            type="submit"
            className="px-5 py-2 bg-blue-600 text-white rounded-xl text-sm font-semibold hover:bg-blue-700 transition-colors flex items-center gap-1.5"
          >
            {mode === "ai" ? "Vraag" : "Zoeken"}
            <ArrowRight className="w-3.5 h-3.5" />
          </button>
        </div>
      </form>

      {/* Autocomplete dropdown — full z-index overlay */}
      {showSugg && suggestions.length > 0 && mode === "search" && (
        <div
          className="absolute left-0 right-0 bg-white border border-t-0 border-blue-300 rounded-b-2xl shadow-2xl overflow-hidden"
          style={{ zIndex: 9999 }}
        >
          {/* Result count */}
          <div className="px-5 py-2 border-b border-gray-100 flex items-center justify-between">
            <span className="text-[11px] text-gray-400">
              <strong className="text-gray-600 font-semibold">{found.toLocaleString("nl-NL")}</strong> resultaten
            </span>
            <span className="text-[11px] text-gray-400">{searchTime}ms</span>
          </div>

          {suggestions.map((s, i) => (
            <button
              key={s.external_id}
              onClick={() => { navigate(`/open-overheid/documents/${s.external_id}`); setShowSugg(false); }}
              className={`w-full flex items-start gap-3 px-5 py-3 text-left transition-colors ${
                i === activeIdx ? "bg-blue-50" : "hover:bg-gray-50"
              }`}
            >
              <SearchLg className="w-4 h-4 text-gray-400 shrink-0 mt-0.5" />
              <div className="min-w-0 flex-1">
                <p className="text-sm text-gray-900 truncate">{s.title}</p>
                <div className="flex items-center gap-1.5 mt-1 flex-wrap">
                  {s.organisation && (
                    <span
                      onClick={(e) => filterByOrg(s.organisation!, e)}
                      className={`inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-medium cursor-pointer hover:ring-1 hover:ring-blue-300 transition-all ${orgColor(s.organisation)}`}
                    >
                      <Building01 className="w-2.5 h-2.5" />
                      {s.organisation}
                    </span>
                  )}
                  {s.category && (
                    <span className="px-2 py-0.5 rounded-full bg-amber-50 text-amber-700 text-[10px] font-medium">
                      {s.category}
                    </span>
                  )}
                </div>
              </div>
            </button>
          ))}

          <button
            onClick={handleSubmit}
            className="w-full flex items-center justify-center gap-2 px-5 py-3 text-xs font-semibold text-blue-600 border-t border-gray-100 hover:bg-blue-50 transition-colors"
          >
            Alle {found.toLocaleString("nl-NL")} resultaten bekijken <ArrowRight className="w-3 h-3" />
          </button>
        </div>
      )}
    </div>
  );
}
