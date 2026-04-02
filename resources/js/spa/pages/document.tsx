import { useEffect, useState } from "react";
import { useParams, Link } from "react-router";
import { ArrowLeft, Calendar, Building01, Tag01, File06 } from "@untitledui/icons";
import { api, type DocumentResponse } from "../lib/api";

export function DocumentPage() {
  const { id } = useParams<{ id: string }>();
  const [doc, setDoc] = useState<DocumentResponse | null>(null);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    if (!id) return;
    api.document(id).then(setDoc).catch(() => {}).finally(() => setLoading(false));
  }, [id]);

  if (loading) {
    return (
      <div className="mx-auto max-w-[1187px] px-4 py-12">
        <div className="animate-pulse space-y-4">
          <div className="h-4 bg-bg-secondary rounded w-24" />
          <div className="h-8 bg-bg-secondary rounded w-3/4" />
          <div className="h-4 bg-bg-secondary rounded w-1/2" />
        </div>
      </div>
    );
  }

  if (!doc) {
    return (
      <div className="mx-auto max-w-[1187px] px-4 py-12 text-center">
        <p className="text-text-tertiary">Document niet gevonden</p>
        <Link to="/zoeken" className="text-sm text-text-brand-secondary mt-2 inline-block">
          Terug naar zoeken
        </Link>
      </div>
    );
  }

  return (
    <div className="mx-auto max-w-[1187px] px-4 py-8">
      {/* Back */}
      <Link
        to="/zoeken"
        className="inline-flex items-center gap-1.5 text-sm text-text-tertiary hover:text-text-primary mb-6 transition-colors"
      >
        <ArrowLeft className="w-4 h-4" />
        Terug
      </Link>

      {/* Title */}
      <h1 className="text-2xl font-bold text-text-primary leading-tight">
        {doc.ai_enhanced_title || doc.title}
      </h1>

      {/* Meta */}
      <div className="flex flex-wrap items-center gap-3 mt-3 text-sm text-text-tertiary">
        {doc.organisation && (
          <span className="flex items-center gap-1.5">
            <Building01 className="w-4 h-4 text-fg-quaternary" />
            {doc.organisation}
          </span>
        )}
        {doc.publication_date && (
          <span className="flex items-center gap-1.5">
            <Calendar className="w-4 h-4 text-fg-quaternary" />
            {new Date(doc.publication_date).toLocaleDateString("nl-NL", {
              year: "numeric", month: "long", day: "numeric",
            })}
          </span>
        )}
        {doc.document_type && (
          <span className="flex items-center gap-1.5">
            <File06 className="w-4 h-4 text-fg-quaternary" />
            {doc.document_type}
          </span>
        )}
      </div>

      {/* AI Summary */}
      {doc.ai_summary && (
        <div className="mt-6 p-4 rounded-xl bg-bg-brand-secondary border border-border-brand">
          <p className="text-xs font-semibold text-text-brand-secondary uppercase tracking-wider mb-2">
            AI Samenvatting
          </p>
          <p className="text-sm text-text-secondary leading-relaxed">{doc.ai_summary}</p>
        </div>
      )}

      {/* Keywords */}
      {doc.ai_keywords && doc.ai_keywords.length > 0 && (
        <div className="mt-4 flex flex-wrap gap-1.5">
          {doc.ai_keywords.map((kw) => (
            <Link
              key={kw}
              to={`/zoeken?q=${encodeURIComponent(kw)}`}
              className="inline-flex items-center gap-1 px-2.5 py-1 rounded-md bg-bg-secondary text-xs font-medium text-text-secondary hover:bg-bg-secondary_hover transition-colors"
            >
              <Tag01 className="w-3 h-3" />
              {kw}
            </Link>
          ))}
        </div>
      )}

      {/* Description */}
      {doc.description && (
        <div className="mt-6">
          <h2 className="text-sm font-semibold text-text-primary mb-2">Omschrijving</h2>
          <p className="text-sm text-text-secondary leading-relaxed">{doc.description}</p>
        </div>
      )}

      {/* Category / Theme */}
      <div className="mt-6 grid grid-cols-2 gap-4">
        {doc.category && (
          <div className="p-3 rounded-lg border border-border-secondary">
            <p className="text-xs text-text-quaternary mb-1">Categorie</p>
            <p className="text-sm font-medium text-text-primary">{doc.category}</p>
          </div>
        )}
        {doc.theme && (
          <div className="p-3 rounded-lg border border-border-secondary">
            <p className="text-xs text-text-quaternary mb-1">Thema</p>
            <p className="text-sm font-medium text-text-primary">{doc.theme}</p>
          </div>
        )}
      </div>
    </div>
  );
}
