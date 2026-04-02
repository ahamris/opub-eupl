import { useEffect, useState } from "react";
import { Link } from "react-router";
import { BarChart01, File06, Zap, SearchLg, Building01, Tag01 } from "@untitledui/icons";

interface Stats {
  total_documents: number;
  total_enriched: number;
  latest_sync: string;
  organisations: { organisation: string; count: number }[];
  themes: { theme: string; count: number }[];
}

export function DashboardPage() {
  const [stats, setStats] = useState<Stats | null>(null);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    fetch("/api/v2/stats")
      .then((r) => r.json())
      .then(setStats)
      .catch(() => {})
      .finally(() => setLoading(false));
  }, []);

  if (loading) {
    return (
      <div className="mx-auto max-w-[1187px] px-4 py-10">
        <div className="animate-pulse space-y-6">
          <div className="h-8 bg-bg-secondary rounded w-48" />
          <div className="grid grid-cols-3 gap-4">
            {[1, 2, 3].map((i) => <div key={i} className="h-28 bg-bg-secondary rounded-xl" />)}
          </div>
        </div>
      </div>
    );
  }

  if (!stats) return null;

  const enrichPct = stats.total_documents > 0
    ? ((stats.total_enriched / stats.total_documents) * 100).toFixed(1)
    : "0";

  return (
    <div className="mx-auto max-w-[1187px] px-4 py-10">
      <div className="mb-8">
        <h1 className="text-2xl font-bold text-text-primary">Dashboard</h1>
        <p className="text-sm text-text-tertiary mt-1">Overzicht van het OPub platform</p>
      </div>

      {/* Stat cards */}
      <div className="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-10">
        {[
          { icon: File06, label: "Documenten", value: stats.total_documents.toLocaleString("nl-NL"), sub: "Totaal geïndexeerd" },
          { icon: Zap, label: "AI Verrijkt", value: stats.total_enriched.toLocaleString("nl-NL"), sub: `${enrichPct}% van totaal` },
          { icon: SearchLg, label: "Laatste sync", value: stats.latest_sync ? new Date(stats.latest_sync).toLocaleDateString("nl-NL") : "—", sub: stats.latest_sync ? new Date(stats.latest_sync).toLocaleTimeString("nl-NL", { hour: "2-digit", minute: "2-digit" }) : "" },
        ].map(({ icon: Icon, label, value, sub }) => (
          <div key={label} className="p-5 rounded-xl border border-border-secondary bg-bg-primary">
            <div className="flex items-center gap-3 mb-3">
              <div className="w-9 h-9 rounded-lg bg-bg-brand-secondary flex items-center justify-center">
                <Icon className="w-4 h-4 text-fg-brand-primary" />
              </div>
              <span className="text-sm font-medium text-text-tertiary">{label}</span>
            </div>
            <p className="text-2xl font-bold text-text-primary">{value}</p>
            <p className="text-xs text-text-quaternary mt-0.5">{sub}</p>
          </div>
        ))}
      </div>

      {/* Top organisaties & thema's */}
      <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div className="rounded-xl border border-border-secondary p-5">
          <div className="flex items-center gap-2 mb-4">
            <Building01 className="w-4 h-4 text-fg-brand-primary" />
            <h2 className="text-sm font-semibold text-text-primary">Top organisaties</h2>
          </div>
          <div className="space-y-2">
            {stats.organisations.slice(0, 10).map((o, i) => (
              <Link
                key={o.organisation}
                to={`/zoeken?q=&organisation=${encodeURIComponent(o.organisation)}`}
                className="flex items-center justify-between py-1.5 px-2 rounded-md hover:bg-bg-primary_hover transition-colors"
              >
                <span className="text-sm text-text-secondary truncate flex-1">{o.organisation}</span>
                <span className="text-xs text-text-quaternary tabular-nums ml-2">{o.count.toLocaleString("nl-NL")}</span>
              </Link>
            ))}
          </div>
        </div>

        <div className="rounded-xl border border-border-secondary p-5">
          <div className="flex items-center gap-2 mb-4">
            <Tag01 className="w-4 h-4 text-fg-brand-primary" />
            <h2 className="text-sm font-semibold text-text-primary">Top thema's</h2>
          </div>
          <div className="space-y-2">
            {stats.themes.slice(0, 10).map((t) => (
              <Link
                key={t.theme}
                to={`/zoeken?q=&theme=${encodeURIComponent(t.theme)}`}
                className="flex items-center justify-between py-1.5 px-2 rounded-md hover:bg-bg-primary_hover transition-colors"
              >
                <span className="text-sm text-text-secondary truncate flex-1">{t.theme}</span>
                <span className="text-xs text-text-quaternary tabular-nums ml-2">{t.count.toLocaleString("nl-NL")}</span>
              </Link>
            ))}
          </div>
        </div>
      </div>
    </div>
  );
}
