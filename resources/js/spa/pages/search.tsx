import { useEffect, useState, useCallback, useRef } from "react";
import { useSearchParams, Link, useNavigate } from "react-router";
import {
  SearchLg,
  Calendar,
  Building01,
  ChevronDown,
  ChevronUp,
  XClose,
  FilterLines,
  File06,
  Tag01,
  Folder,
  ArrowLeft,
  ArrowRight,
  ArrowUp,
  ArrowDown,
  Clock,
  ChevronRight,
  Bell01,
  Stars02,
  LayersThree01,
  Zap,
  Target04,
} from "@untitledui/icons";
import { api, type SearchHit, type SearchResponse, type FacetCount } from "../lib/api";
import { SubscribeModal } from "../components/features/subscribe-modal";

/* ─── Types & Constants ────────────────────────────────────── */

type SortOption = "relevance" | "date_desc" | "date_asc" | "modified_desc" | "modified_asc";

const SORT_FIELDS = [
  { field: "relevance", label: "Relevantie", toggleable: false },
  { field: "date", label: "Publicatiedatum", toggleable: true, desc: "date_desc" as SortOption, asc: "date_asc" as SortOption },
  { field: "modified", label: "Laatst gewijzigd", toggleable: true, desc: "modified_desc" as SortOption, asc: "modified_asc" as SortOption },
];

const PER_PAGE_OPTIONS = [10, 20, 50, 100] as const;

const FACET_CONFIG = [
  { key: "category", label: "Categorie", icon: Tag01 },
  { key: "organisation", label: "Organisatie", icon: Building01 },
  { key: "theme", label: "Onderwerp", icon: Folder },
  { key: "document_type", label: "Documenttype", icon: File06 },
] as const;

const FACET_LABELS: Record<string, string> = {
  category: "Categorie",
  organisation: "Organisatie",
  theme: "Onderwerp",
  document_type: "Documenttype",
};

const FACET_ICONS: Record<string, typeof Tag01> = {
  category: Tag01,
  organisation: Building01,
  theme: Folder,
  document_type: File06,
};

const INITIAL_FACET_COUNT = 6;
const DEFAULT_PER_PAGE = 20;

/* Facet data from API (array format) */
interface ApiFacet {
  field_name: string;
  counts: { value: string; count: number; highlighted?: string }[];
}

/* Autocomplete filter suggestion */
interface FilterSuggestion {
  facetKey: string;
  facetLabel: string;
  value: string;
  count: number;
}

/* ─── Component ────────────────────────────────────────────── */

export function SearchPage() {
  const navigate = useNavigate();
  const [params, setParams] = useSearchParams();
  const q = params.get("q") || "";
  const page = parseInt(params.get("page") || "1");
  const sort = (params.get("sort") as SortOption) || "relevance";
  const perPage = parseInt(params.get("per_page") || String(DEFAULT_PER_PAGE));

  // Active filters from URL (pipe-separated multi-values)
  const activeFilters: Record<string, string[]> = {};
  for (const { key } of FACET_CONFIG) {
    const v = params.get(key);
    if (v) activeFilters[key] = v.split("|");
  }
  const dateFrom = params.get("date_from") || "";
  const dateTo = params.get("date_to") || "";

  // State
  const [query, setQuery] = useState(q);
  const [data, setData] = useState<SearchResponse | null>(null);
  const [loading, setLoading] = useState(false);
  const [expandedFacets, setExpandedFacets] = useState<Record<string, boolean>>({});
  const [collapsedSections, setCollapsedSections] = useState<Record<string, boolean>>({});
  const [mobileFiltersOpen, setMobileFiltersOpen] = useState(false);
  const [subscribeOpen, setSubscribeOpen] = useState(false);
  const [semantic, setSemantic] = useState(params.get("semantic") === "1");
  const [groupBy, setGroupBy] = useState(params.get("group_by") || "");
  const [enrichedOnly, setEnrichedOnly] = useState(params.get("enriched") === "1");
  const [exactMatch, setExactMatch] = useState(params.get("exact") === "1");

  // Date local state
  const [localDateFrom, setLocalDateFrom] = useState(dateFrom);
  const [localDateTo, setLocalDateTo] = useState(dateTo);

  // Autocomplete state
  const [acOpen, setAcOpen] = useState(false);
  const [acSuggestions, setAcSuggestions] = useState<SearchHit[]>([]);
  const [acFilterSuggestions, setAcFilterSuggestions] = useState<FilterSuggestion[]>([]);
  const [acActiveIdx, setAcActiveIdx] = useState(-1);
  const [acLoading, setAcLoading] = useState(false);
  const acDebounce = useRef<ReturnType<typeof setTimeout>>(undefined);

  // All known facet values (loaded on mount)
  const [allFacets, setAllFacets] = useState<ApiFacet[]>([]);

  const inputRef = useRef<HTMLInputElement>(null);
  const acWrapRef = useRef<HTMLDivElement>(null);

  // Sync URL state to local state
  useEffect(() => { setLocalDateFrom(dateFrom); setLocalDateTo(dateTo); }, [dateFrom, dateTo]);
  useEffect(() => { setQuery(q); }, [q]);

  // Load all facet values on mount
  useEffect(() => {
    api.search("*", 1, 1, {}).then((r) => {
      const facets = r.facets;
      if (Array.isArray(facets)) setAllFacets(facets);
    }).catch(() => {});
  }, []);

  // Fetch search results — always show results (recent docs when no query)
  useEffect(() => {
    setLoading(true);
    const searchQuery = q || "*";
    const filters: Record<string, string> = {};
    for (const [k, vals] of Object.entries(activeFilters)) {
      filters[k] = vals.join("|");
    }
    // When no query, default to latest publications
    if (!q && sort === "relevance") {
      filters.sort = "date_desc";
    } else if (sort !== "relevance") {
      filters.sort = sort;
    }
    // Default to last 7 days when no query and no date filters
    if (!q && !dateFrom && !dateTo) {
      const weekAgo = new Date();
      weekAgo.setDate(weekAgo.getDate() - 7);
      filters.date_from = weekAgo.toISOString().split("T")[0];
    }
    if (dateFrom) filters.date_from = dateFrom;
    if (dateTo) filters.date_to = dateTo;
    if (enrichedOnly) filters.enriched = "1";
    if (exactMatch) filters.exact = "1";

    api.search(searchQuery, page, perPage, filters, {
        semantic: q ? semantic : false,
        group_by: groupBy || undefined,
      })
      .then((r) => {
        setData(r);
        if (Array.isArray(r.facets) && Object.keys(activeFilters).length === 0) {
          setAllFacets(r.facets as unknown as ApiFacet[]);
        }
      })
      .catch(() => setData(null))
      .finally(() => setLoading(false));
  }, [q, page, perPage, sort, JSON.stringify(activeFilters), dateFrom, dateTo, semantic, groupBy, enrichedOnly, exactMatch]);

  // Close autocomplete on outside click
  useEffect(() => {
    const handler = (e: MouseEvent) => {
      if (acWrapRef.current && !acWrapRef.current.contains(e.target as Node)) {
        setAcOpen(false);
      }
    };
    document.addEventListener("mousedown", handler);
    return () => document.removeEventListener("mousedown", handler);
  }, []);

  // Autocomplete: fetch suggestions + match filters
  const fetchAutocomplete = useCallback(
    (text: string) => {
      if (text.length < 2) {
        setAcSuggestions([]);
        setAcFilterSuggestions([]);
        setAcOpen(false);
        return;
      }

      clearTimeout(acDebounce.current);
      acDebounce.current = setTimeout(async () => {
        setAcLoading(true);

        // Match against known facet values
        const lower = text.toLowerCase();
        const filterMatches: FilterSuggestion[] = [];
        for (const facet of allFacets) {
          const facetKey = facet.field_name;
          if (!FACET_LABELS[facetKey]) continue;
          for (const c of facet.counts) {
            if (c.value && c.value.toLowerCase().includes(lower)) {
              filterMatches.push({
                facetKey,
                facetLabel: FACET_LABELS[facetKey],
                value: c.value,
                count: c.count,
              });
            }
          }
        }
        // Sort by count descending, limit to 5
        filterMatches.sort((a, b) => b.count - a.count);
        setAcFilterSuggestions(filterMatches.slice(0, 5));

        // Fetch document suggestions
        try {
          const res = await fetch(`/api/v2/search?q=${encodeURIComponent(text)}&per_page=5`);
          const data = await res.json();
          setAcSuggestions(
            (data.hits || []).map((h: any) => ({
              id: h.id,
              external_id: h.external_id,
              title: h.title,
              organisation: h.organisation,
              category: h.category,
              document_type: h.document_type,
              theme: h.theme,
              description: h.description,
              publication_date: h.publication_date,
            })),
          );
        } catch {
          setAcSuggestions([]);
        }

        setAcOpen(true);
        setAcActiveIdx(-1);
        setAcLoading(false);
      }, 100);
    },
    [allFacets],
  );

  useEffect(() => { fetchAutocomplete(query); }, [query, fetchAutocomplete]);

  // URL param helpers
  const updateParams = useCallback(
    (updates: Record<string, string | null>) => {
      setParams((prev) => {
        const next = new URLSearchParams(prev);
        for (const [k, v] of Object.entries(updates)) {
          if (v === null || v === "") next.delete(k);
          else next.set(k, v);
        }
        if (!("page" in updates)) next.delete("page");
        return next;
      });
    },
    [setParams],
  );

  const handleSearch = (e?: React.FormEvent) => {
    e?.preventDefault();
    if (query.trim()) {
      updateParams({ q: query.trim(), page: null });
      setAcOpen(false);
    }
  };

  const applyFilter = (key: string, value: string) => {
    const current = activeFilters[key] || [];
    const has = current.includes(value);
    const next = has ? current.filter((v) => v !== value) : [...current, value];
    updateParams({ [key]: next.length > 0 ? next.join("|") : null });
    setAcOpen(false);
  };

  const clearAllFilters = () => {
    const clears: Record<string, null> = {};
    for (const { key } of FACET_CONFIG) clears[key] = null;
    clears.date_from = null;
    clears.date_to = null;
    clears.sort = null;
    updateParams(clears);
  };

  const applyDateFilter = () => {
    updateParams({ date_from: localDateFrom || null, date_to: localDateTo || null });
  };

  const setSort = (s: SortOption) => {
    updateParams({ sort: s === "relevance" ? null : s });
  };

  const setPerPageOption = (pp: number) => {
    updateParams({ per_page: pp === DEFAULT_PER_PAGE ? null : String(pp) });
  };

  const goToPage = (p: number) => {
    updateParams({ page: p === 1 ? null : String(p) });
    window.scrollTo({ top: 0, behavior: "smooth" });
  };

  // Autocomplete keyboard nav
  const totalAcItems = acFilterSuggestions.length + acSuggestions.length;
  const handleKey = (e: React.KeyboardEvent) => {
    if (!acOpen || totalAcItems === 0) {
      if (e.key === "Enter") handleSearch();
      return;
    }
    if (e.key === "ArrowDown") {
      e.preventDefault();
      setAcActiveIdx((i) => Math.min(i + 1, totalAcItems - 1));
    } else if (e.key === "ArrowUp") {
      e.preventDefault();
      setAcActiveIdx((i) => Math.max(i - 1, -1));
    } else if (e.key === "Enter") {
      e.preventDefault();
      if (acActiveIdx < 0) {
        handleSearch();
      } else if (acActiveIdx < acFilterSuggestions.length) {
        const fs = acFilterSuggestions[acActiveIdx];
        applyFilter(fs.facetKey, fs.value);
      } else {
        const docIdx = acActiveIdx - acFilterSuggestions.length;
        navigate(`/open-overheid/documents/${acSuggestions[docIdx].external_id}`);
        setAcOpen(false);
      }
    } else if (e.key === "Escape") {
      setAcOpen(false);
    }
  };

  // Derived state
  const facets = data?.facets || allFacets;
  const totalPages = data?.total_pages || 0;
  const found = data?.found || 0;
  const searchTime = data?.search_time_ms || 0;
  const hasActiveFilters = Object.keys(activeFilters).length > 0 || !!dateFrom || !!dateTo;

  const getFacetCounts = (key: string): FacetCount[] => {
    if (!facets) return [];
    if (Array.isArray(facets)) {
      const match = (facets as ApiFacet[]).find((f) => f.field_name === key);
      return (match?.counts || []).filter((c) => c.value); // filter empty values
    }
    const rec = facets as Record<string, FacetCount[]>;
    if (Array.isArray(rec[key])) return rec[key];
    return [];
  };

  /* ─── Render ──────────────────────────────────────────────── */

  return (
    <div className="min-h-[calc(100vh-3.5rem)]">
      {/* ── Brand header with centered search ── */}
      <div className="bg-brand-700">
        <div className="mx-auto max-w-[1187px] px-4 sm:px-6 py-10 sm:py-14 text-center">
          <p className="text-sm font-semibold text-brand-200 mb-2">Zoeken</p>
          <h1 className="text-2xl sm:text-3xl font-bold text-white mb-2">
            Doorzoek overheidsdocumenten
          </h1>
          <p className="text-brand-200 text-sm mb-8 max-w-md mx-auto">
            Zoek, filter en vind documenten van alle aangesloten bestuursorganen
          </p>

          {/* Combined search + sort + pagination bar */}
          <div ref={acWrapRef} className="relative max-w-3xl mx-auto">
            <div className="bg-white rounded-xl shadow-lg overflow-hidden">
              {/* Search row */}
              <form onSubmit={handleSearch}>
                <div className="flex items-center px-5 py-5 gap-3">
                  <SearchLg className="w-5 h-5 text-blue-500 shrink-0" />
                  <input
                    ref={inputRef}
                    type="text"
                    value={query}
                    onChange={(e) => setQuery(e.target.value)}
                    onFocus={() => {
                      if (query.length >= 2 && (acFilterSuggestions.length > 0 || acSuggestions.length > 0))
                        setAcOpen(true);
                    }}
                    onKeyDown={handleKey}
                    placeholder="Zoek op trefwoord, organisatie, onderwerp..."
                    className="flex-1 min-w-0 bg-transparent border-0 outline-none text-[15px] text-gray-900 placeholder:text-gray-400"
                    autoComplete="off"
                  />
                  {query && (
                    <button
                      type="button"
                      onClick={() => { setQuery(""); setAcOpen(false); inputRef.current?.focus(); }}
                      className="p-1 rounded-md text-gray-400 hover:text-gray-600 hover:bg-gray-100 transition-colors shrink-0"
                    >
                      <XClose className="w-3.5 h-3.5" />
                    </button>
                  )}
                </div>
              </form>

              {/* Sort + per-page + pagination row */}
              <div className="flex items-center border-t border-gray-100 px-5 py-2 gap-1.5 text-xs overflow-x-auto">
                <span className="text-gray-400 font-medium shrink-0">Sorteer op:</span>
                {SORT_FIELDS.map((sf) => {
                  const isActiveField = sf.field === "relevance"
                    ? sort === "relevance"
                    : sort === sf.desc || sort === sf.asc;
                  const isDesc = sort === sf.desc;

                  const handleSortClick = () => {
                    if (!sf.toggleable) {
                      setSort("relevance");
                    } else if (!isActiveField) {
                      setSort(sf.desc!);
                    } else {
                      setSort(isDesc ? sf.asc! : sf.desc!);
                    }
                  };

                  return (
                    <button
                      key={sf.field}
                      onClick={handleSortClick}
                      className={`flex items-center gap-0.5 px-2 py-1 rounded-md font-medium shrink-0 transition-colors ${
                        isActiveField
                          ? "bg-brand-700 text-white"
                          : "text-gray-500 hover:text-gray-700 hover:bg-gray-50"
                      }`}
                    >
                      {sf.toggleable && isActiveField && (
                        isDesc ? <ArrowDown className="w-3 h-3" /> : <ArrowUp className="w-3 h-3" />
                      )}
                      {sf.label}
                    </button>
                  );
                })}

                <div className="flex-1" />

                <span className="text-gray-400 font-medium shrink-0">Aantal:</span>
                {PER_PAGE_OPTIONS.map((pp) => (
                  <button
                    key={pp}
                    onClick={() => setPerPageOption(pp)}
                    className={`px-2 py-1 rounded-md font-medium shrink-0 transition-colors ${
                      perPage === pp
                        ? "bg-gray-800 text-white"
                        : "text-gray-500 hover:text-gray-700 hover:bg-gray-50"
                    }`}
                  >
                    {pp}
                  </button>
                ))}

                {totalPages > 1 && (
                  <>
                    <div className="w-px h-4 bg-gray-200 mx-1 shrink-0" />
                    <button
                      onClick={() => goToPage(page - 1)}
                      disabled={page <= 1}
                      className="p-1 rounded text-gray-400 hover:text-gray-600 disabled:opacity-30 disabled:cursor-not-allowed shrink-0"
                    >
                      <ArrowLeft className="w-3.5 h-3.5" />
                    </button>
                    <span className="tabular-nums text-gray-500 shrink-0">
                      <span className="font-semibold text-gray-700">{page}</span>
                      <span className="text-gray-300 mx-0.5">/</span>
                      {totalPages.toLocaleString("nl-NL")}
                    </span>
                    <button
                      onClick={() => goToPage(page + 1)}
                      disabled={page >= totalPages}
                      className="p-1 rounded text-gray-400 hover:text-gray-600 disabled:opacity-30 disabled:cursor-not-allowed shrink-0"
                    >
                      <ArrowRight className="w-3.5 h-3.5" />
                    </button>
                  </>
                )}
              </div>
            </div>

            {/* ── Autocomplete dropdown (command palette) ── */}
            {acOpen && (acFilterSuggestions.length > 0 || acSuggestions.length > 0) && (
              <div className="absolute left-0 right-0 top-full mt-1.5 bg-white border border-gray-200 rounded-xl shadow-2xl overflow-hidden z-50 text-left">
                {/* Filter suggestions */}
                {acFilterSuggestions.length > 0 && (
                  <div>
                    <div className="px-4 pt-3 pb-1.5">
                      <span className="text-[10px] font-semibold text-gray-400 uppercase tracking-wider">
                        Filter toepassen
                      </span>
                    </div>
                    {acFilterSuggestions.map((fs, i) => {
                      const Icon = FACET_ICONS[fs.facetKey] || Tag01;
                      const isActive = acActiveIdx === i;
                      return (
                        <button
                          key={`${fs.facetKey}-${fs.value}`}
                          onClick={() => applyFilter(fs.facetKey, fs.value)}
                          className={`w-full flex items-center gap-3 px-4 py-2.5 text-left transition-colors ${
                            isActive ? "bg-blue-50" : "hover:bg-gray-50"
                          }`}
                        >
                          <span className="w-7 h-7 rounded-lg bg-blue-50 flex items-center justify-center shrink-0">
                            <Icon className="w-3.5 h-3.5 text-blue-600" />
                          </span>
                          <div className="flex-1 min-w-0">
                            <span className="text-sm text-gray-900">{fs.value}</span>
                            <span className="text-xs text-gray-400 ml-2">{fs.facetLabel}</span>
                          </div>
                          <span className="text-xs text-gray-400 tabular-nums shrink-0">
                            {fs.count.toLocaleString("nl-NL")}
                          </span>
                        </button>
                      );
                    })}
                  </div>
                )}

                {/* Document suggestions */}
                {acSuggestions.length > 0 && (
                  <div>
                    <div className={`px-4 pt-3 pb-1.5 ${acFilterSuggestions.length > 0 ? "border-t border-gray-100" : ""}`}>
                      <span className="text-[10px] font-semibold text-gray-400 uppercase tracking-wider">
                        Documenten
                      </span>
                    </div>
                    {acSuggestions.map((s, i) => {
                      const idx = acFilterSuggestions.length + i;
                      const isActive = acActiveIdx === idx;
                      return (
                        <button
                          key={s.external_id}
                          onClick={() => {
                            navigate(`/open-overheid/documents/${s.external_id}`);
                            setAcOpen(false);
                          }}
                          className={`w-full flex items-center gap-3 px-4 py-2.5 text-left transition-colors ${
                            isActive ? "bg-blue-50" : "hover:bg-gray-50"
                          }`}
                        >
                          <span className="w-7 h-7 rounded-lg bg-gray-100 flex items-center justify-center shrink-0">
                            <File06 className="w-3.5 h-3.5 text-gray-500" />
                          </span>
                          <div className="flex-1 min-w-0">
                            <p className="text-sm text-gray-900 truncate">{s.title}</p>
                            <p className="text-xs text-gray-400 truncate">
                              {[s.organisation, s.document_type].filter(Boolean).join(" · ")}
                            </p>
                          </div>
                          <ChevronRight className="w-3.5 h-3.5 text-gray-300 shrink-0" />
                        </button>
                      );
                    })}
                  </div>
                )}

                {/* Search all */}
                <button
                  onClick={handleSearch}
                  className="w-full flex items-center justify-center gap-2 px-4 py-3 text-xs font-semibold text-blue-600 bg-gray-50/50 border-t border-gray-100 hover:bg-blue-50 transition-colors"
                >
                  Alle resultaten voor &ldquo;{query}&rdquo; bekijken
                  <ArrowRight className="w-3 h-3" />
                </button>
              </div>
            )}
          </div>
        </div>
      </div>

      {/* ── Sub-bar: result count + active filters ── */}
      {hasActiveFilters && (
        <div className="border-b border-gray-100 bg-white">
          <div className="mx-auto max-w-[1187px] px-4 sm:px-6">
            <div className="flex flex-wrap items-center gap-1.5 py-2.5">
              {Object.entries(activeFilters).flatMap(([key, values]) => {
                const Icon = FACET_ICONS[key] || Tag01;
                return values.map((value) => (
                  <span
                    key={`${key}-${value}`}
                    className="inline-flex items-center gap-1 pl-2 pr-1 py-0.5 rounded-md bg-blue-50 border border-blue-100 text-blue-700 text-xs font-medium"
                  >
                    <Icon className="w-3 h-3" />
                    {value}
                    <button
                      onClick={() => applyFilter(key, value)}
                      className="p-0.5 rounded hover:bg-blue-100 transition-colors"
                    >
                      <XClose className="w-2.5 h-2.5" />
                    </button>
                  </span>
                ));
              })}
              {dateFrom && (
                <span className="inline-flex items-center gap-1 pl-2 pr-1 py-0.5 rounded-md bg-blue-50 border border-blue-100 text-blue-700 text-xs font-medium">
                  <Calendar className="w-3 h-3" />
                  Vanaf {formatDate(dateFrom)}
                  <button onClick={() => updateParams({ date_from: null })} className="p-0.5 rounded hover:bg-blue-100 transition-colors">
                    <XClose className="w-2.5 h-2.5" />
                  </button>
                </span>
              )}
              {dateTo && (
                <span className="inline-flex items-center gap-1 pl-2 pr-1 py-0.5 rounded-md bg-blue-50 border border-blue-100 text-blue-700 text-xs font-medium">
                  <Calendar className="w-3 h-3" />
                  Tot {formatDate(dateTo)}
                  <button onClick={() => updateParams({ date_to: null })} className="p-0.5 rounded hover:bg-blue-100 transition-colors">
                    <XClose className="w-2.5 h-2.5" />
                  </button>
                </span>
              )}
              <button
                onClick={clearAllFilters}
                className="text-[11px] text-gray-400 hover:text-blue-600 font-medium transition-colors"
              >
                Wis alles
              </button>
            </div>
          </div>
        </div>
      )}

      {/* ── Main grid: sidebar + results ── */}
      <div className="mx-auto max-w-[1187px] px-4 sm:px-6 pt-6 pb-12">
        <div className="flex gap-8 items-start">
          {/* ── Filter sidebar (always visible) ── */}
          <FilterSidebar
            mobileOpen={mobileFiltersOpen}
            onMobileClose={() => setMobileFiltersOpen(false)}
            facetConfig={FACET_CONFIG}
            getFacetCounts={getFacetCounts}
            activeFilters={activeFilters}
            toggleFacet={applyFilter}
            expandedFacets={expandedFacets}
            setExpandedFacets={setExpandedFacets}
            collapsedSections={collapsedSections}
            setCollapsedSections={setCollapsedSections}
            localDateFrom={localDateFrom}
            localDateTo={localDateTo}
            setLocalDateFrom={setLocalDateFrom}
            setLocalDateTo={setLocalDateTo}
            applyDateFilter={applyDateFilter}
            dateFrom={dateFrom}
            dateTo={dateTo}
          />

          {/* ── Results column ── */}
          <div className="flex-1 min-w-0">
            {/* Result count + subscribe + mobile filter */}
            <div className="mb-3 flex items-center justify-between">
              <div className="flex items-center gap-3">
                {!loading && data ? (
                  <p className="text-sm text-gray-500">
                    <span className="font-semibold text-gray-900">{found.toLocaleString("nl-NL")}</span>{" "}
                    {q ? "resultaten" : "recente publicaties"}
                    <span className="text-gray-300 mx-1.5">·</span>
                    <span className="text-gray-400">{searchTime}ms</span>
                  </p>
                ) : loading ? (
                  <p className="text-sm text-gray-400 flex items-center gap-2">
                    <span className="w-3.5 h-3.5 border-[1.5px] border-blue-400 border-t-transparent rounded-full animate-spin" />
                    {q ? "Zoeken..." : "Laden..."}
                  </p>
                ) : null}
                <div className="flex items-center gap-1.5 flex-wrap">
                    {/* Semantic search toggle */}
                    {q && <button
                      onClick={() => {
                        setSemantic(!semantic);
                        const next = new URLSearchParams(params);
                        if (!semantic) next.set("semantic", "1"); else next.delete("semantic");
                        setParams(next, { replace: true });
                      }}
                      className={`flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg text-xs font-semibold transition-colors border ${
                        semantic
                          ? "text-purple-700 bg-purple-50 border-purple-200 hover:bg-purple-100"
                          : "text-text-quaternary bg-bg-primary border-border-secondary hover:bg-bg-primary_hover hover:text-text-secondary"
                      }`}
                      title="Semantisch zoeken vindt conceptueel gerelateerde resultaten, ook als de exacte woorden niet voorkomen"
                    >
                      <Stars02 className={`w-3.5 h-3.5 ${semantic ? "text-purple-500" : ""}`} />
                      Semantisch
                    </button>}

                    {/* Group by toggle */}
                    <button
                      onClick={() => {
                        const next = new URLSearchParams(params);
                        const newGroup = groupBy ? "" : "organisation";
                        setGroupBy(newGroup);
                        if (newGroup) next.set("group_by", newGroup); else next.delete("group_by");
                        setParams(next, { replace: true });
                      }}
                      className={`flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg text-xs font-semibold transition-colors border ${
                        groupBy
                          ? "text-blue-700 bg-blue-50 border-blue-200 hover:bg-blue-100"
                          : "text-text-quaternary bg-bg-primary border-border-secondary hover:bg-bg-primary_hover hover:text-text-secondary"
                      }`}
                      title="Groepeer resultaten per organisatie"
                    >
                      <LayersThree01 className={`w-3.5 h-3.5 ${groupBy ? "text-blue-500" : ""}`} />
                      Groeperen
                    </button>

                    {/* AI Enriched only */}
                    <button
                      onClick={() => {
                        const next = new URLSearchParams(params);
                        setEnrichedOnly(!enrichedOnly);
                        if (!enrichedOnly) next.set("enriched", "1"); else next.delete("enriched");
                        setParams(next, { replace: true });
                      }}
                      className={`flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg text-xs font-semibold transition-colors border ${
                        enrichedOnly
                          ? "text-emerald-700 bg-emerald-50 border-emerald-200 hover:bg-emerald-100"
                          : "text-text-quaternary bg-bg-primary border-border-secondary hover:bg-bg-primary_hover hover:text-text-secondary"
                      }`}
                      title="Toon alleen documenten met AI-samenvatting en trefwoorden"
                    >
                      <Zap className={`w-3.5 h-3.5 ${enrichedOnly ? "text-emerald-500" : ""}`} />
                      AI Verrijkt
                    </button>

                    {/* Exact match */}
                    {q && <button
                      onClick={() => {
                        const next = new URLSearchParams(params);
                        setExactMatch(!exactMatch);
                        if (!exactMatch) next.set("exact", "1"); else next.delete("exact");
                        setParams(next, { replace: true });
                      }}
                      className={`flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg text-xs font-semibold transition-colors border ${
                        exactMatch
                          ? "text-red-700 bg-red-50 border-red-200 hover:bg-red-100"
                          : "text-text-quaternary bg-bg-primary border-border-secondary hover:bg-bg-primary_hover hover:text-text-secondary"
                      }`}
                      title="Zoek alleen op exacte woordovereenkomst (geen typo-correctie of synoniemen)"
                    >
                      <Target04 className={`w-3.5 h-3.5 ${exactMatch ? "text-red-500" : ""}`} />
                      Exact
                    </button>}

                    {/* Subscribe / Attendering */}
                    <button
                      onClick={() => setSubscribeOpen(true)}
                      className="flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg text-xs font-semibold text-orange-700 bg-orange-50 border border-orange-200 hover:bg-orange-100 hover:border-orange-300 transition-colors"
                    >
                      <Bell01 className="w-3.5 h-3.5 text-orange-500" />
                      Attendering
                    </button>
                  </div>
              </div>
              <button
                onClick={() => setMobileFiltersOpen(!mobileFiltersOpen)}
                className="lg:hidden flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-medium text-gray-500 hover:text-gray-700 hover:bg-gray-50 transition-colors"
              >
                <FilterLines className="w-3.5 h-3.5" />
                Filters
                {Object.keys(activeFilters).length > 0 && (
                  <span className="w-4 h-4 rounded-full bg-blue-600 text-white text-[9px] font-bold flex items-center justify-center">
                    {Object.keys(activeFilters).length}
                  </span>
                )}
              </button>
            </div>

            {/* Loading skeleton */}
            {loading && (
              <div className="space-y-2">
                {Array.from({ length: 8 }).map((_, i) => (
                  <div key={i} className="p-4 rounded-xl bg-white border border-gray-100 animate-pulse">
                    <div className="h-4 bg-gray-100 rounded w-3/4 mb-2.5" />
                    <div className="h-3 bg-gray-100 rounded w-full mb-2" />
                    <div className="h-3 bg-gray-100 rounded w-1/2" />
                  </div>
                ))}
              </div>
            )}

            {/* Grouped results */}
            {!loading && data && data.groups && data.groups.length > 0 && (
              <>
                <div className="space-y-4">
                  {data.groups.map((group) => (
                    <div key={group.group_key} className="rounded-xl border border-gray-200 bg-white overflow-hidden">
                      <div className="flex items-center justify-between px-4 py-3 bg-gray-50 border-b border-gray-100">
                        <Link
                          to={`/organisaties/${encodeURIComponent(group.group_key)}`}
                          className="flex items-center gap-2 text-sm font-semibold text-gray-900 hover:text-blue-700 transition-colors"
                        >
                          <Building01 className="w-4 h-4 text-gray-400" />
                          {group.group_key}
                        </Link>
                        <span className="text-xs text-gray-400">{group.found.toLocaleString("nl-NL")} documenten</span>
                      </div>
                      <div className="divide-y divide-gray-50">
                        {group.hits.map((hit) => (
                          <div key={hit.external_id} className="px-4 py-2.5">
                            <Link
                              to={`/open-overheid/documents/${hit.external_id}`}
                              className="text-sm font-medium text-gray-800 hover:text-blue-700 transition-colors line-clamp-1"
                            >
                              {hit.title}
                            </Link>
                            {hit.publication_date && (
                              <span className="text-xs text-gray-400 ml-2">{formatDate(hit.publication_date)}</span>
                            )}
                          </div>
                        ))}
                      </div>
                    </div>
                  ))}
                </div>
              </>
            )}

            {/* Results list (flat) */}
            {!loading && data && !data.groups && data.hits.length > 0 && (
              <>
                <div className="space-y-2">
                  {data.hits.map((hit) => (
                    <ResultCard key={hit.external_id} hit={hit} />
                  ))}
                </div>

                {/* Subscribe CTA banner */}
                <div className="mt-4 rounded-xl border-2 border-orange-200 bg-gradient-to-r from-orange-50 to-amber-50 p-4 flex items-center gap-4">
                  <div className="w-10 h-10 rounded-xl bg-orange-100 flex items-center justify-center flex-shrink-0">
                    <Bell01 className="w-5 h-5 text-orange-600" />
                  </div>
                  <div className="flex-1 min-w-0">
                    <p className="text-sm font-semibold text-text-primary">
                      Nieuwe publicaties ontvangen?
                    </p>
                    <p className="text-xs text-text-tertiary mt-0.5">
                      Stel een attendering in voor {q ? <>&ldquo;{q}&rdquo;</> : "deze filters"} en ontvang meldingen per e-mail
                    </p>
                  </div>
                  <button
                    onClick={() => setSubscribeOpen(true)}
                    className="px-4 py-2 bg-orange-500 text-white rounded-lg text-sm font-semibold hover:bg-orange-600 transition duration-100 ease-linear flex items-center gap-2 whitespace-nowrap"
                  >
                    <Bell01 className="w-4 h-4" />
                    Attendering
                  </button>
                </div>

                {/* Bottom pagination */}
                {totalPages > 1 && (
                  <nav className="mt-6 flex items-center justify-between">
                    <button
                      onClick={() => goToPage(page - 1)}
                      disabled={page <= 1}
                      className="flex items-center gap-1.5 px-3 py-2 rounded-lg text-sm font-medium text-gray-500 hover:bg-white hover:text-gray-700 disabled:opacity-30 disabled:cursor-not-allowed transition-all"
                    >
                      <ArrowLeft className="w-4 h-4" />
                      Vorige
                    </button>
                    <div className="flex items-center gap-1">
                      {paginationRange(page, totalPages).map((p, i) =>
                        p === "..." ? (
                          <span key={`d${i}`} className="w-9 h-9 flex items-center justify-center text-sm text-gray-400">
                            ···
                          </span>
                        ) : (
                          <button
                            key={p}
                            onClick={() => goToPage(p as number)}
                            className={`w-9 h-9 rounded-lg text-sm font-medium transition-all ${
                              page === p
                                ? "bg-brand-700 text-white"
                                : "text-gray-500 hover:bg-white"
                            }`}
                          >
                            {p}
                          </button>
                        ),
                      )}
                    </div>
                    <button
                      onClick={() => goToPage(page + 1)}
                      disabled={page >= totalPages}
                      className="flex items-center gap-1.5 px-3 py-2 rounded-lg text-sm font-medium text-gray-500 hover:bg-white hover:text-gray-700 disabled:opacity-30 disabled:cursor-not-allowed transition-all"
                    >
                      Volgende
                      <ArrowRight className="w-4 h-4" />
                    </button>
                  </nav>
                )}
              </>
            )}

            {/* Empty state — no results */}
            {!loading && data && data.hits.length === 0 && !data.groups?.length && (
              <div className="text-center py-16">
                <div className="w-14 h-14 rounded-2xl bg-gray-100 flex items-center justify-center mx-auto mb-4">
                  <SearchLg className="w-6 h-6 text-gray-400" />
                </div>
                <p className="text-gray-700 font-semibold">
                  {q ? <>Geen resultaten voor &ldquo;{q}&rdquo;</> : "Geen recente publicaties gevonden"}
                </p>
                <p className="text-sm text-gray-400 mt-1 max-w-xs mx-auto">
                  {q ? "Probeer andere zoektermen of pas de filters aan" : "Pas de datumfilters aan om meer documenten te zien"}
                </p>
                {hasActiveFilters && (
                  <button
                    onClick={clearAllFilters}
                    className="mt-3 px-4 py-1.5 text-sm font-medium text-blue-600 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors"
                  >
                    Filters wissen
                  </button>
                )}
              </div>
            )}
          </div>
        </div>
      </div>

      {/* Subscribe modal */}
      <SubscribeModal
        open={subscribeOpen}
        onClose={() => setSubscribeOpen(false)}
        searchQuery={q}
        activeFilters={activeFilters}
      />
    </div>
  );
}

/* ─── Result Card ──────────────────────────────────────────── */

function ResultCard({ hit }: { hit: SearchHit }) {
  return (
    <Link
      to={`/open-overheid/documents/${hit.external_id}`}
      className="group flex items-start gap-4 p-4 rounded-xl bg-white/50 border border-gray-100 hover:bg-white hover:border-blue-200/60 hover:shadow-md hover:shadow-blue-50/40 transition-all duration-150"
    >
      <div className="min-w-0 flex-1">
        <h3 className="text-[15px] font-semibold text-gray-900 group-hover:text-blue-700 transition-colors line-clamp-2 leading-snug">
          {hit.title}
        </h3>
        {hit.description && (
          <p className="mt-1 text-sm text-gray-500 line-clamp-2 leading-relaxed">{hit.description}</p>
        )}
        <div className="mt-2 flex flex-wrap items-center gap-x-3 gap-y-1">
          {hit.organisation && (
            <Link
              to={`/organisaties/${encodeURIComponent(hit.organisation)}`}
              onClick={(e) => e.stopPropagation()}
              className="flex items-center gap-1 text-xs text-gray-500 hover:text-blue-600 transition-colors"
            >
              <Building01 className="w-3 h-3 text-gray-400" />
              {hit.organisation}
            </Link>
          )}
          {hit.publication_date && (
            <span className="flex items-center gap-1 text-xs text-gray-400">
              <Clock className="w-3 h-3" />
              {formatDate(hit.publication_date)}
            </span>
          )}
          {hit.document_type && (
            <span className="flex items-center gap-1 text-xs text-gray-400">
              <File06 className="w-3 h-3" />
              {hit.document_type}
            </span>
          )}
        </div>
        {(hit.category || hit.theme) && (
          <div className="mt-2 flex flex-wrap items-center gap-1.5">
            {hit.category && (
              <span className="px-1.5 py-0.5 rounded bg-gray-100 text-gray-500 text-[11px] font-medium">
                {hit.category}
              </span>
            )}
            {hit.theme && (
              <span className="px-1.5 py-0.5 rounded bg-blue-50 text-blue-600 text-[11px] font-medium">
                {hit.theme}
              </span>
            )}
          </div>
        )}
      </div>
      <ChevronRight className="w-4 h-4 text-gray-300 group-hover:text-blue-500 shrink-0 mt-1 transition-colors" />
    </Link>
  );
}

/* ─── Filter Sidebar ───────────────────────────────────────── */

interface FilterSidebarProps {
  mobileOpen: boolean;
  onMobileClose: () => void;
  facetConfig: typeof FACET_CONFIG;
  getFacetCounts: (key: string) => FacetCount[];
  activeFilters: Record<string, string[]>;
  toggleFacet: (key: string, value: string) => void;
  expandedFacets: Record<string, boolean>;
  setExpandedFacets: React.Dispatch<React.SetStateAction<Record<string, boolean>>>;
  collapsedSections: Record<string, boolean>;
  setCollapsedSections: React.Dispatch<React.SetStateAction<Record<string, boolean>>>;
  localDateFrom: string;
  localDateTo: string;
  setLocalDateFrom: (v: string) => void;
  setLocalDateTo: (v: string) => void;
  applyDateFilter: () => void;
  dateFrom: string;
  dateTo: string;
}

function FilterSidebar({
  mobileOpen,
  onMobileClose,
  facetConfig,
  getFacetCounts,
  activeFilters,
  toggleFacet,
  expandedFacets,
  setExpandedFacets,
  collapsedSections,
  setCollapsedSections,
  localDateFrom,
  localDateTo,
  setLocalDateFrom,
  setLocalDateTo,
  applyDateFilter,
  dateFrom,
  dateTo,
}: FilterSidebarProps) {
  const toggleSection = (key: string) =>
    setCollapsedSections((p) => ({ ...p, [key]: !p[key] }));
  const toggleExpand = (key: string) =>
    setExpandedFacets((p) => ({ ...p, [key]: !p[key] }));

  const dateChanged = localDateFrom !== dateFrom || localDateTo !== dateTo;

  const sidebar = (
    <div className="space-y-2">
      {/* Date range */}
      <SidebarSection
        label="Publicatiedatum"
        icon={Calendar}
        collapsed={collapsedSections.date}
        onToggle={() => toggleSection("date")}
      >
        <div className="px-3 pb-3 space-y-2">
          <div>
            <label className="text-[10px] font-semibold text-gray-400 uppercase tracking-wider">Vanaf</label>
            <input
              type="date"
              value={localDateFrom}
              onChange={(e) => setLocalDateFrom(e.target.value)}
              className="w-full mt-1 px-2.5 py-1.5 rounded-lg border border-gray-200 bg-white text-sm text-gray-700 focus:border-blue-300 focus:ring-1 focus:ring-blue-100 outline-none transition-all"
            />
          </div>
          <div>
            <label className="text-[10px] font-semibold text-gray-400 uppercase tracking-wider">Tot en met</label>
            <input
              type="date"
              value={localDateTo}
              onChange={(e) => setLocalDateTo(e.target.value)}
              className="w-full mt-1 px-2.5 py-1.5 rounded-lg border border-gray-200 bg-white text-sm text-gray-700 focus:border-blue-300 focus:ring-1 focus:ring-blue-100 outline-none transition-all"
            />
          </div>
          {dateChanged && (
            <button
              onClick={applyDateFilter}
              className="w-full py-1.5 text-xs font-semibold text-blue-700 bg-blue-50 hover:bg-blue-100 rounded-lg transition-colors"
            >
              Toepassen
            </button>
          )}
        </div>
      </SidebarSection>

      {/* Facets */}
      {facetConfig.map(({ key, label, icon }) => {
        const counts = getFacetCounts(key);
        if (counts.length === 0) return null;

        const isExpanded = expandedFacets[key];
        const visible = isExpanded ? counts : counts.slice(0, INITIAL_FACET_COUNT);
        const hasMore = counts.length > INITIAL_FACET_COUNT;

        return (
          <SidebarSection
            key={key}
            label={label}
            icon={icon}
            collapsed={collapsedSections[key]}
            onToggle={() => toggleSection(key)}
            badge={(activeFilters[key]?.length ?? 0) > 0}
          >
            <div className="px-1.5 pb-2">
              <div className="max-h-60 overflow-y-auto space-y-px">
                {visible.map(({ value, count }) => {
                  const isActive = activeFilters[key]?.includes(value) ?? false;
                  return (
                    <button
                      key={value}
                      onClick={() => toggleFacet(key, value)}
                      className={`w-full flex items-center gap-2 px-2 py-[7px] rounded-lg text-left text-[13px] transition-all ${
                        isActive
                          ? "bg-blue-50 text-blue-700 font-medium"
                          : "text-gray-600 hover:bg-gray-50"
                      }`}
                    >
                      <span
                        className={`w-3.5 h-3.5 rounded border-[1.5px] flex items-center justify-center shrink-0 transition-all ${
                          isActive ? "bg-blue-600 border-blue-600" : "border-gray-300 bg-white"
                        }`}
                      >
                        {isActive && (
                          <svg className="w-2 h-2 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth={4}>
                            <path strokeLinecap="round" strokeLinejoin="round" d="M5 13l4 4L19 7" />
                          </svg>
                        )}
                      </span>
                      <span className="truncate flex-1">{value}</span>
                      <span className={`text-[11px] tabular-nums shrink-0 ${isActive ? "text-blue-500" : "text-gray-400"}`}>
                        {count.toLocaleString("nl-NL")}
                      </span>
                    </button>
                  );
                })}
              </div>
              {hasMore && (
                <button
                  onClick={() => toggleExpand(key)}
                  className="w-full mt-1 py-1 text-[11px] font-medium text-blue-600 hover:text-blue-800 transition-colors"
                >
                  {isExpanded ? "Toon minder" : `Toon meer (${counts.length - INITIAL_FACET_COUNT})`}
                </button>
              )}
            </div>
          </SidebarSection>
        );
      })}
    </div>
  );

  return (
    <>
      {/* Desktop */}
      <aside className="hidden lg:block w-[260px] shrink-0 sticky top-[7.5rem]">{sidebar}</aside>

      {/* Mobile overlay */}
      {mobileOpen && (
        <div className="fixed inset-0 z-50 lg:hidden">
          <div className="absolute inset-0 bg-black/20 backdrop-blur-sm" onClick={onMobileClose} />
          <div className="absolute right-0 top-0 h-full w-80 max-w-[85vw] bg-white shadow-2xl overflow-y-auto">
            <div className="flex items-center justify-between px-4 py-3 border-b border-gray-100">
              <span className="text-sm font-semibold text-gray-800">Filters</span>
              <button onClick={onMobileClose} className="p-1 rounded-lg hover:bg-gray-100 text-gray-500">
                <XClose className="w-5 h-5" />
              </button>
            </div>
            <div className="p-3">{sidebar}</div>
          </div>
        </div>
      )}
    </>
  );
}

/* ─── Sidebar Section ──────────────────────────────────────── */

function SidebarSection({
  label,
  icon: Icon,
  collapsed,
  onToggle,
  badge,
  children,
}: {
  label: string;
  icon: React.FC<{ className?: string }>;
  collapsed?: boolean;
  onToggle: () => void;
  badge?: boolean;
  children: React.ReactNode;
}) {
  return (
    <div className="rounded-xl bg-white/70 backdrop-blur-sm border border-gray-100 overflow-hidden">
      <button
        onClick={onToggle}
        className="w-full flex items-center justify-between px-3 py-2.5 hover:bg-gray-50 transition-colors"
      >
        <span className="flex items-center gap-2 text-[13px] font-semibold text-gray-700">
          <Icon className="w-4 h-4 text-gray-400" />
          {label}
          {badge && <span className="w-1.5 h-1.5 rounded-full bg-blue-500" />}
        </span>
        {collapsed ? (
          <ChevronDown className="w-3.5 h-3.5 text-gray-400" />
        ) : (
          <ChevronUp className="w-3.5 h-3.5 text-gray-400" />
        )}
      </button>
      {!collapsed && children}
    </div>
  );
}

/* ─── Helpers ──────────────────────────────────────────────── */

function formatDate(d: string): string {
  try {
    return new Date(d).toLocaleDateString("nl-NL", { day: "numeric", month: "short", year: "numeric" });
  } catch {
    return d;
  }
}

function paginationRange(current: number, total: number): (number | "...")[] {
  if (total <= 7) return Array.from({ length: total }, (_, i) => i + 1);
  const pages: (number | "...")[] = [1];
  if (current > 3) pages.push("...");
  for (let i = Math.max(2, current - 1); i <= Math.min(total - 1, current + 1); i++) pages.push(i);
  if (current < total - 2) pages.push("...");
  pages.push(total);
  return pages;
}
