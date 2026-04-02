import { useEffect, useState } from "react";
import { useSearchParams, Link } from "react-router";
import { SearchLg, Calendar, Building01, ChevronRight } from "@untitledui/icons";
import { api, type SearchHit } from "../lib/api";

export function SearchPage() {
  const [params, setParams] = useSearchParams();
  const q = params.get("q") || "";
  const page = parseInt(params.get("page") || "1");

  const [query, setQuery] = useState(q);
  const [hits, setHits] = useState<SearchHit[]>([]);
  const [found, setFound] = useState(0);
  const [loading, setLoading] = useState(false);

  useEffect(() => {
    if (!q) return;
    setLoading(true);
    api.search(q, page).then((r) => {
      setHits(r.hits);
      setFound(r.found);
    }).catch(() => {}).finally(() => setLoading(false));
  }, [q, page]);

  const handleSearch = (e: React.FormEvent) => {
    e.preventDefault();
    if (query.trim()) setParams({ q: query.trim() });
  };

  return (
    <div className="mx-auto max-w-[1187px] px-4 py-8">
      {/* SearchLg input */}
      <form onSubmit={handleSearch} className="mb-8">
        <div className="flex items-center gap-2 border border-border-primary rounded-xl px-4 py-2.5 focus-within:border-border-brand focus-within:ring-4 focus-within:ring-ring-brand-shadow transition-all">
          <SearchLg className="w-5 h-5 text-fg-quaternary" />
          <input
            type="text"
            value={query}
            onChange={(e) => setQuery(e.target.value)}
            placeholder="Zoek in overheidsdocumenten..."
            className="flex-1 bg-transparent border-0 outline-none text-text-primary placeholder:text-text-placeholder"
          />
          <button type="submit" className="px-4 py-1.5 bg-bg-brand-solid text-text-white rounded-lg text-sm font-semibold hover:bg-bg-brand-solid_hover transition-colors">
            Zoeken
          </button>
        </div>
      </form>

      {/* Results count */}
      {q && (
        <p className="text-sm text-text-tertiary mb-4">
          {loading ? "Zoeken..." : `${found.toLocaleString("nl-NL")} resultaten voor "${q}"`}
        </p>
      )}

      {/* Results */}
      <div className="space-y-3">
        {hits.map((hit) => (
          <Link
            key={hit.external_id}
            to={`/open-overheid/documents/${hit.external_id}`}
            className="block p-4 rounded-xl border border-border-secondary hover:border-border-brand hover:shadow-xs transition-all group"
          >
            <div className="flex items-start justify-between gap-4">
              <div className="min-w-0 flex-1">
                <h3 className="text-sm font-semibold text-text-primary group-hover:text-text-brand-secondary transition-colors line-clamp-2">
                  {hit.title}
                </h3>
                {hit.description && (
                  <p className="mt-1.5 text-sm text-text-tertiary line-clamp-2">
                    {hit.description}
                  </p>
                )}
                <div className="mt-2 flex flex-wrap items-center gap-3 text-xs text-text-quaternary">
                  {hit.organisation && (
                    <span className="flex items-center gap-1">
                      <Building01 className="w-3 h-3" />
                      {hit.organisation}
                    </span>
                  )}
                  {hit.publication_date && (
                    <span className="flex items-center gap-1">
                      <Calendar className="w-3 h-3" />
                      {hit.publication_date}
                    </span>
                  )}
                  {hit.category && (
                    <span className="px-1.5 py-0.5 rounded bg-bg-secondary text-text-quaternary">
                      {hit.category}
                    </span>
                  )}
                </div>
              </div>
              <ChevronRight className="w-4 h-4 text-fg-quaternary group-hover:text-fg-brand-primary shrink-0 mt-1 transition-colors" />
            </div>
          </Link>
        ))}
      </div>

      {/* Empty state */}
      {!loading && q && hits.length === 0 && (
        <div className="text-center py-16">
          <p className="text-text-tertiary">Geen resultaten gevonden voor "{q}"</p>
          <p className="text-sm text-text-quaternary mt-1">Probeer andere zoektermen</p>
        </div>
      )}
    </div>
  );
}
