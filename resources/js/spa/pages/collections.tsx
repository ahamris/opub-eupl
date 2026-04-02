import { useEffect, useState } from "react";
import { Link } from "react-router";
import { Folder, ChevronRight, Building01, Calendar } from "@untitledui/icons";
import { api, type SearchHit } from "../lib/api";

export function CollectionsPage() {
  const [dossiers, setDossiers] = useState<SearchHit[]>([]);
  const [loading, setLoading] = useState(true);
  const [page, setPage] = useState(1);
  const [totalPages, setTotalPages] = useState(1);

  useEffect(() => {
    setLoading(true);
    api.dossiers(page).then((r) => {
      setDossiers(r.data);
      setTotalPages(r.total_pages);
    }).catch(() => {}).finally(() => setLoading(false));
  }, [page]);

  return (
    <div className="mx-auto max-w-[1187px] px-4 py-10">
      <div className="mb-8">
        <h1 className="text-2xl font-bold text-text-primary">Collecties</h1>
        <p className="text-sm text-text-tertiary mt-1">Samenhangende overheidsdocumenten gegroepeerd in dossiers</p>
      </div>

      {loading ? (
        <div className="space-y-3">
          {[...Array(6)].map((_, i) => (
            <div key={i} className="animate-pulse h-20 bg-bg-secondary rounded-xl" />
          ))}
        </div>
      ) : dossiers.length === 0 ? (
        <div className="text-center py-20">
          <Folder className="w-12 h-12 text-fg-quaternary mx-auto mb-3" />
          <p className="text-text-tertiary">Geen collecties gevonden</p>
        </div>
      ) : (
        <>
          <div className="grid gap-3">
            {dossiers.map((d) => (
              <Link
                key={d.external_id}
                to={`/collecties/${d.external_id}`}
                className="flex items-start gap-4 p-5 rounded-xl border border-border-secondary hover:border-border-brand hover:shadow-xs transition-all group bg-bg-primary"
              >
                <div className="w-10 h-10 rounded-lg bg-bg-brand-secondary flex items-center justify-center shrink-0">
                  <Folder className="w-5 h-5 text-fg-brand-primary" />
                </div>
                <div className="flex-1 min-w-0">
                  <h3 className="text-sm font-semibold text-text-primary group-hover:text-text-brand-secondary transition-colors line-clamp-2">
                    {d.title}
                  </h3>
                  {d.description && (
                    <p className="text-sm text-text-tertiary mt-1 line-clamp-2">{d.description}</p>
                  )}
                  <div className="flex items-center gap-3 mt-2 text-xs text-text-quaternary">
                    {d.organisation && (
                      <span className="flex items-center gap-1">
                        <Building01 className="w-3 h-3" /> {d.organisation}
                      </span>
                    )}
                    {d.publication_date && (
                      <span className="flex items-center gap-1">
                        <Calendar className="w-3 h-3" /> {d.publication_date}
                      </span>
                    )}
                  </div>
                </div>
                <ChevronRight className="w-4 h-4 text-fg-quaternary group-hover:text-fg-brand-primary shrink-0 mt-2 transition-colors" />
              </Link>
            ))}
          </div>

          {/* Pagination */}
          {totalPages > 1 && (
            <div className="flex items-center justify-center gap-2 mt-8">
              <button
                onClick={() => setPage(Math.max(1, page - 1))}
                disabled={page === 1}
                className="px-3 py-1.5 text-sm border border-border-primary rounded-lg disabled:opacity-40 hover:bg-bg-primary_hover transition-colors"
              >
                Vorige
              </button>
              <span className="text-sm text-text-tertiary px-3">
                {page} / {totalPages}
              </span>
              <button
                onClick={() => setPage(Math.min(totalPages, page + 1))}
                disabled={page === totalPages}
                className="px-3 py-1.5 text-sm border border-border-primary rounded-lg disabled:opacity-40 hover:bg-bg-primary_hover transition-colors"
              >
                Volgende
              </button>
            </div>
          )}
        </>
      )}
    </div>
  );
}
