import { useEffect, useRef, useState } from "react";
import { Link } from "react-router";
import { BarChart01, File06, Zap, SearchLg, Building01, Tag01, ArrowRight } from "@untitledui/icons";
import ApexCharts from "apexcharts";
import { api, type StatsResponse } from "../lib/api";

function BarChart({ categories, series, color, horizontal = true }: {
  categories: string[];
  series: number[];
  color: string;
  horizontal?: boolean;
}) {
  const ref = useRef<HTMLDivElement>(null);
  const chartRef = useRef<ApexCharts | null>(null);

  useEffect(() => {
    if (!ref.current || categories.length === 0) return;

    const options: ApexCharts.ApexOptions = {
      chart: {
        type: "bar",
        height: horizontal ? Math.max(280, categories.length * 32) : 280,
        toolbar: { show: false },
        fontFamily: "Inter, sans-serif",
      },
      plotOptions: {
        bar: {
          horizontal,
          borderRadius: 4,
          barHeight: horizontal ? "65%" : undefined,
          columnWidth: horizontal ? undefined : "55%",
        },
      },
      dataLabels: { enabled: false },
      xaxis: {
        categories,
        labels: {
          style: { colors: "#667085", fontSize: "12px" },
          ...(horizontal ? { formatter: (v: string) => Number(v).toLocaleString("nl-NL") } : {}),
        },
      },
      yaxis: {
        labels: {
          style: { colors: "#667085", fontSize: "12px" },
          ...(!horizontal ? { formatter: (v: number) => v.toLocaleString("nl-NL") } : {}),
          ...(horizontal ? { maxWidth: 200 } : {}),
        },
      },
      series: [{ name: "Documenten", data: series }],
      colors: [color],
      grid: { borderColor: "#EAECF0", strokeDashArray: 3 },
      tooltip: {
        y: { formatter: (v: number) => v.toLocaleString("nl-NL") },
      },
    };

    chartRef.current = new ApexCharts(ref.current, options);
    chartRef.current.render();

    return () => { chartRef.current?.destroy(); };
  }, [categories, series, color, horizontal]);

  return <div ref={ref} />;
}

function AreaChart({ categories, series }: { categories: string[]; series: number[] }) {
  const ref = useRef<HTMLDivElement>(null);
  const chartRef = useRef<ApexCharts | null>(null);

  useEffect(() => {
    if (!ref.current || categories.length === 0) return;

    const options: ApexCharts.ApexOptions = {
      chart: {
        type: "area",
        height: 260,
        toolbar: { show: false },
        fontFamily: "Inter, sans-serif",
        sparkline: { enabled: false },
      },
      dataLabels: { enabled: false },
      stroke: { curve: "smooth", width: 2 },
      fill: {
        type: "gradient",
        gradient: { shadeIntensity: 1, opacityFrom: 0.4, opacityTo: 0.05, stops: [0, 90, 100] },
      },
      xaxis: {
        categories,
        labels: { style: { colors: "#667085", fontSize: "11px" } },
      },
      yaxis: {
        labels: {
          style: { colors: "#667085", fontSize: "12px" },
          formatter: (v: number) => v.toLocaleString("nl-NL"),
        },
      },
      series: [{ name: "Publicaties", data: series }],
      colors: ["#155EEF"],
      grid: { borderColor: "#EAECF0", strokeDashArray: 3 },
      tooltip: {
        y: { formatter: (v: number) => v.toLocaleString("nl-NL") },
      },
    };

    chartRef.current = new ApexCharts(ref.current, options);
    chartRef.current.render();

    return () => { chartRef.current?.destroy(); };
  }, [categories, series]);

  return <div ref={ref} />;
}

export function DashboardPage() {
  const [stats, setStats] = useState<StatsResponse | null>(null);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    api.stats()
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
          <div className="grid grid-cols-2 gap-6">
            {[1, 2].map((i) => <div key={i} className="h-72 bg-bg-secondary rounded-xl" />)}
          </div>
        </div>
      </div>
    );
  }

  if (!stats) return null;

  const enrichPct = stats.total_documents > 0
    ? ((stats.total_enriched / stats.total_documents) * 100).toFixed(1)
    : "0";

  const monthLabels = (stats.monthly_publications || []).map((m) => {
    const [y, mo] = m.month.split("-");
    return new Date(Number(y), Number(mo) - 1).toLocaleDateString("nl-NL", { month: "short", year: "2-digit" });
  });
  const monthValues = (stats.monthly_publications || []).map((m) => m.count);

  const orgNames = stats.organisations.slice(0, 10).map((o) => o.organisation);
  const orgCounts = stats.organisations.slice(0, 10).map((o) => o.count);

  const themeNames = stats.themes.slice(0, 10).map((t) => t.theme);
  const themeCounts = stats.themes.slice(0, 10).map((t) => t.count);

  const catNames = (stats.top_categories || []).slice(0, 8).map((c) => c.category);
  const catCounts = (stats.top_categories || []).slice(0, 8).map((c) => c.count);

  return (
    <div className="mx-auto max-w-[1187px] px-4 py-10 animate-fade-in">
      <div className="mb-8">
        <h1 className="text-2xl font-bold text-text-primary">Dashboard</h1>
        <p className="text-sm text-text-tertiary mt-1">Overzicht van het OPub platform</p>
      </div>

      {/* Stat cards */}
      <div className="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-10">
        {[
          { icon: File06, label: "Documenten", value: stats.total_documents.toLocaleString("nl-NL"), sub: "Totaal geindexeerd" },
          { icon: Zap, label: "AI Verrijkt", value: stats.total_enriched.toLocaleString("nl-NL"), sub: `${enrichPct}% van totaal` },
          { icon: SearchLg, label: "Laatste sync", value: stats.latest_sync ? new Date(stats.latest_sync).toLocaleDateString("nl-NL") : "\u2014", sub: stats.latest_sync ? new Date(stats.latest_sync).toLocaleTimeString("nl-NL", { hour: "2-digit", minute: "2-digit" }) : "" },
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

      {/* Publication timeline */}
      {monthLabels.length > 0 && (
        <div className="rounded-xl border border-border-secondary p-5 mb-6">
          <div className="flex items-center gap-2 mb-2">
            <BarChart01 className="w-4 h-4 text-fg-brand-primary" />
            <h2 className="text-sm font-semibold text-text-primary">Publicaties per maand</h2>
            <span className="text-xs text-text-quaternary">(afgelopen 12 maanden)</span>
          </div>
          <AreaChart categories={monthLabels} series={monthValues} />
        </div>
      )}

      {/* Organisation & theme charts */}
      <div className="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <div className="rounded-xl border border-border-secondary p-5">
          <div className="flex items-center gap-2 mb-2">
            <Building01 className="w-4 h-4 text-fg-brand-primary" />
            <h2 className="text-sm font-semibold text-text-primary">Top organisaties</h2>
          </div>
          <BarChart categories={orgNames} series={orgCounts} color="#155EEF" />
          <div className="mt-3 space-y-1">
            {stats.organisations.slice(0, 10).map((o) => (
              <Link
                key={o.organisation}
                to={`/organisaties/${encodeURIComponent(o.organisation)}`}
                className="flex items-center justify-between py-1.5 px-2 rounded-md hover:bg-bg-primary_hover transition duration-100 ease-linear group"
              >
                <span className="text-sm text-text-secondary truncate flex-1">{o.organisation}</span>
                <div className="flex items-center gap-2">
                  <span className="text-xs text-text-quaternary tabular-nums">{o.count.toLocaleString("nl-NL")}</span>
                  <ArrowRight className="w-3.5 h-3.5 text-fg-quaternary opacity-0 group-hover:opacity-100 transition duration-100 ease-linear" />
                </div>
              </Link>
            ))}
          </div>
        </div>

        <div className="rounded-xl border border-border-secondary p-5">
          <div className="flex items-center gap-2 mb-2">
            <Tag01 className="w-4 h-4 text-fg-brand-primary" />
            <h2 className="text-sm font-semibold text-text-primary">Top thema's</h2>
          </div>
          <BarChart categories={themeNames} series={themeCounts} color="#7A5AF8" />
          <div className="mt-3 space-y-1">
            {stats.themes.slice(0, 10).map((t) => (
              <Link
                key={t.theme}
                to={`/zoeken?q=&theme=${encodeURIComponent(t.theme)}`}
                className="flex items-center justify-between py-1.5 px-2 rounded-md hover:bg-bg-primary_hover transition duration-100 ease-linear"
              >
                <span className="text-sm text-text-secondary truncate flex-1">{t.theme}</span>
                <span className="text-xs text-text-quaternary tabular-nums">{t.count.toLocaleString("nl-NL")}</span>
              </Link>
            ))}
          </div>
        </div>
      </div>

      {/* Categories chart */}
      {catNames.length > 0 && (
        <div className="rounded-xl border border-border-secondary p-5">
          <div className="flex items-center gap-2 mb-2">
            <File06 className="w-4 h-4 text-fg-brand-primary" />
            <h2 className="text-sm font-semibold text-text-primary">Top categorieën</h2>
          </div>
          <BarChart categories={catNames} series={catCounts} color="#12B76A" horizontal={false} />
        </div>
      )}
    </div>
  );
}
