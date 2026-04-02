import { useEffect, useRef, useState } from "react";
import { useParams, Link } from "react-router";
import {
  Building01, File06, Tag01, Zap, ArrowLeft, Calendar,
  ArrowRight, BarChart01, Bell01, Mail01, Phone01, Globe02,
  MarkerPin01, Send01, FileCheck02, Check, XClose, AlertTriangle,
  LinkExternal01, BookOpen01,
} from "@untitledui/icons";
import ApexCharts from "apexcharts";
import { api, type OrganisationResponse, type BestuursorgaanInfo } from "../lib/api";

/* ─── Charts ──────────────────────────────────────────────── */

function AreaChart({ categories, series }: { categories: string[]; series: number[] }) {
  const ref = useRef<HTMLDivElement>(null);
  const chartRef = useRef<ApexCharts | null>(null);

  useEffect(() => {
    if (!ref.current || categories.length === 0) return;
    chartRef.current = new ApexCharts(ref.current, {
      chart: { type: "area", height: 220, toolbar: { show: false }, fontFamily: "Inter, sans-serif" },
      dataLabels: { enabled: false },
      stroke: { curve: "smooth", width: 2 },
      fill: { type: "gradient", gradient: { shadeIntensity: 1, opacityFrom: 0.4, opacityTo: 0.05, stops: [0, 90, 100] } },
      xaxis: { categories, labels: { style: { colors: "#667085", fontSize: "11px" } } },
      yaxis: { labels: { style: { colors: "#667085", fontSize: "12px" }, formatter: (v: number) => v.toLocaleString("nl-NL") } },
      series: [{ name: "Publicaties", data: series }],
      colors: ["#155EEF"],
      grid: { borderColor: "#EAECF0", strokeDashArray: 3 },
      tooltip: { y: { formatter: (v: number) => v.toLocaleString("nl-NL") } },
    });
    chartRef.current.render();
    return () => { chartRef.current?.destroy(); };
  }, [categories, series]);

  return <div ref={ref} />;
}

function HorizontalBar({ categories, series, color }: { categories: string[]; series: number[]; color: string }) {
  const ref = useRef<HTMLDivElement>(null);
  const chartRef = useRef<ApexCharts | null>(null);

  useEffect(() => {
    if (!ref.current || categories.length === 0) return;
    chartRef.current = new ApexCharts(ref.current, {
      chart: { type: "bar", height: Math.max(200, categories.length * 30), toolbar: { show: false }, fontFamily: "Inter, sans-serif" },
      plotOptions: { bar: { horizontal: true, borderRadius: 4, barHeight: "65%" } },
      dataLabels: { enabled: false },
      xaxis: { categories, labels: { style: { colors: "#667085", fontSize: "12px" }, formatter: (v: string) => Number(v).toLocaleString("nl-NL") } },
      yaxis: { labels: { style: { colors: "#667085", fontSize: "12px" }, maxWidth: 180 } },
      series: [{ name: "Documenten", data: series }],
      colors: [color],
      grid: { borderColor: "#EAECF0", strokeDashArray: 3 },
      tooltip: { y: { formatter: (v: number) => v.toLocaleString("nl-NL") } },
    });
    chartRef.current.render();
    return () => { chartRef.current?.destroy(); };
  }, [categories, series, color]);

  return <div ref={ref} />;
}

/* ─── Subscribe Inline CTA ────────────────────────────────── */

function SubscribeCTA({ organisationName }: { organisationName: string }) {
  const [email, setEmail] = useState("");
  const [frequency, setFrequency] = useState("daily");
  const [loading, setLoading] = useState(false);
  const [success, setSuccess] = useState(false);
  const [error, setError] = useState("");

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    if (!email.trim()) return;
    setLoading(true);
    setError("");

    const csrf = document.querySelector<HTMLMetaElement>('meta[name="csrf-token"]')?.content || "";
    try {
      const res = await fetch("/api/subscriptions", {
        method: "POST",
        headers: { "Content-Type": "application/json", Accept: "application/json", "X-CSRF-TOKEN": csrf },
        credentials: "same-origin",
        body: JSON.stringify({
          email: email.trim(),
          frequency,
          search_query: null,
          filters: { organisation: organisationName },
        }),
      });
      if (res.status === 409) { setError("U heeft al een attendering voor deze organisatie."); return; }
      if (!res.ok) throw new Error("Er ging iets mis.");
      setSuccess(true);
    } catch (err: any) {
      setError(err.message);
    } finally {
      setLoading(false);
    }
  };

  if (success) {
    return (
      <div className="rounded-xl border-2 border-green-200 bg-green-50 p-5">
        <div className="flex items-center gap-3">
          <div className="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center flex-shrink-0">
            <Check className="w-5 h-5 text-green-600" />
          </div>
          <div>
            <p className="text-sm font-semibold text-green-800">Attendering aangemaakt</p>
            <p className="text-xs text-green-600 mt-0.5">
              Bevestigingsmail verzonden naar <strong>{email}</strong>. Klik op de link in de e-mail om te activeren.
            </p>
          </div>
        </div>
      </div>
    );
  }

  return (
    <div className="rounded-xl border-2 border-orange-200 bg-gradient-to-r from-orange-50 to-amber-50 p-5">
      <div className="flex items-start gap-4">
        <div className="w-11 h-11 rounded-xl bg-orange-100 flex items-center justify-center flex-shrink-0">
          <Bell01 className="w-5 h-5 text-orange-600" />
        </div>
        <div className="flex-1 min-w-0">
          <h3 className="text-base font-semibold text-text-primary">Attendering instellen</h3>
          <p className="text-sm text-text-tertiary mt-0.5">
            Ontvang een e-mail bij nieuwe publicaties van <strong className="text-text-secondary">{organisationName}</strong>
          </p>

          <form onSubmit={handleSubmit} className="mt-3">
            <div className="flex flex-col sm:flex-row gap-2">
              <div className="flex items-center gap-2 flex-1 bg-white border border-orange-200 rounded-lg px-3 py-2 focus-within:border-orange-400 focus-within:ring-2 focus-within:ring-orange-100 transition-all">
                <Mail01 className="w-4 h-4 text-orange-400 shrink-0" />
                <input
                  type="email"
                  value={email}
                  onChange={(e) => setEmail(e.target.value)}
                  placeholder="uw@email.nl"
                  required
                  className="flex-1 bg-transparent border-0 outline-none text-sm text-text-primary placeholder:text-text-placeholder"
                />
              </div>
              <select
                value={frequency}
                onChange={(e) => setFrequency(e.target.value)}
                className="bg-white border border-orange-200 rounded-lg px-3 py-2 text-sm text-text-secondary focus:border-orange-400 focus:ring-2 focus:ring-orange-100 outline-none"
              >
                <option value="daily">Dagelijks</option>
                <option value="weekly">Wekelijks</option>
                <option value="immediate">Direct</option>
              </select>
              <button
                type="submit"
                disabled={loading || !email.trim()}
                className="px-4 py-2 bg-orange-500 text-white rounded-lg text-sm font-semibold hover:bg-orange-600 disabled:opacity-50 transition duration-100 ease-linear flex items-center justify-center gap-2 whitespace-nowrap"
              >
                {loading ? (
                  <span className="w-4 h-4 border-2 border-white/30 border-t-white rounded-full animate-spin" />
                ) : (
                  <Bell01 className="w-4 h-4" />
                )}
                Attendering
              </button>
            </div>
            {error && (
              <p className="text-xs text-red-600 mt-2 flex items-center gap-1">
                <AlertTriangle className="w-3 h-3" /> {error}
              </p>
            )}
          </form>
        </div>
      </div>
    </div>
  );
}

/* ─── WOO Verzoek Form ────────────────────────────────────── */

function WooVerzoekForm({ organisationName, wooEmail, wooAdres }: {
  organisationName: string;
  wooEmail?: string | null;
  wooAdres?: string | null;
}) {
  const [open, setOpen] = useState(false);
  const [form, setForm] = useState({ naam: "", email: "", onderwerp: "", omschrijving: "" });
  const [loading, setLoading] = useState(false);
  const [success, setSuccess] = useState(false);
  const [error, setError] = useState("");

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    setLoading(true);
    setError("");
    try {
      await api.wooVerzoek({ organisation: organisationName, ...form });
      setSuccess(true);
    } catch (err: any) {
      setError(err.message || "Er ging iets mis.");
    } finally {
      setLoading(false);
    }
  };

  if (success) {
    return (
      <div className="rounded-xl border-2 border-green-200 bg-green-50 p-5">
        <div className="flex items-center gap-3">
          <div className="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center flex-shrink-0">
            <FileCheck02 className="w-5 h-5 text-green-600" />
          </div>
          <div>
            <p className="text-sm font-semibold text-green-800">Woo-verzoek ingediend</p>
            <p className="text-xs text-green-600 mt-0.5">
              Uw verzoek is geregistreerd. {wooEmail && <>U kunt het ook direct mailen naar <strong>{wooEmail}</strong>.</>}
            </p>
          </div>
        </div>
      </div>
    );
  }

  return (
    <div className="rounded-xl border border-border-secondary bg-bg-primary">
      <button
        onClick={() => setOpen(!open)}
        className="w-full flex items-center justify-between p-5 text-left"
      >
        <div className="flex items-center gap-3">
          <div className="w-10 h-10 rounded-xl bg-brand-50 flex items-center justify-center flex-shrink-0">
            <BookOpen01 className="w-5 h-5 text-brand-600" />
          </div>
          <div>
            <h3 className="text-sm font-semibold text-text-primary">Woo-verzoek indienen</h3>
            <p className="text-xs text-text-tertiary mt-0.5">Vraag overheidsinformatie op via de Wet open overheid</p>
          </div>
        </div>
        <ArrowRight className={`w-4 h-4 text-fg-quaternary transition duration-100 ease-linear ${open ? "rotate-90" : ""}`} />
      </button>

      {open && (
        <div className="px-5 pb-5 border-t border-border-secondary pt-4">
          {(wooEmail || wooAdres) && (
            <div className="rounded-lg bg-bg-secondary p-3 mb-4 text-xs text-text-tertiary space-y-1">
              <p className="font-medium text-text-secondary">Woo-contactgegevens</p>
              {wooEmail && (
                <p className="flex items-center gap-1.5">
                  <Mail01 className="w-3 h-3" />
                  <a href={`mailto:${wooEmail}`} className="text-text-brand-secondary hover:underline">{wooEmail}</a>
                </p>
              )}
              {wooAdres && (
                <p className="flex items-center gap-1.5">
                  <MarkerPin01 className="w-3 h-3" /> {wooAdres}
                </p>
              )}
            </div>
          )}

          <form onSubmit={handleSubmit} className="space-y-3">
            <div className="grid grid-cols-1 sm:grid-cols-2 gap-3">
              <div>
                <label className="text-xs font-medium text-text-secondary block mb-1">Uw naam</label>
                <input
                  type="text"
                  value={form.naam}
                  onChange={(e) => setForm({ ...form, naam: e.target.value })}
                  required
                  className="w-full border border-border-primary rounded-lg px-3 py-2 text-sm text-text-primary outline-none focus:border-border-brand focus:ring-2 focus:ring-ring-brand-shadow"
                />
              </div>
              <div>
                <label className="text-xs font-medium text-text-secondary block mb-1">E-mailadres</label>
                <input
                  type="email"
                  value={form.email}
                  onChange={(e) => setForm({ ...form, email: e.target.value })}
                  required
                  className="w-full border border-border-primary rounded-lg px-3 py-2 text-sm text-text-primary outline-none focus:border-border-brand focus:ring-2 focus:ring-ring-brand-shadow"
                />
              </div>
            </div>
            <div>
              <label className="text-xs font-medium text-text-secondary block mb-1">Onderwerp</label>
              <input
                type="text"
                value={form.onderwerp}
                onChange={(e) => setForm({ ...form, onderwerp: e.target.value })}
                required
                placeholder="Waar gaat uw verzoek over?"
                className="w-full border border-border-primary rounded-lg px-3 py-2 text-sm text-text-primary placeholder:text-text-placeholder outline-none focus:border-border-brand focus:ring-2 focus:ring-ring-brand-shadow"
              />
            </div>
            <div>
              <label className="text-xs font-medium text-text-secondary block mb-1">Omschrijving</label>
              <textarea
                value={form.omschrijving}
                onChange={(e) => setForm({ ...form, omschrijving: e.target.value })}
                required
                rows={4}
                placeholder="Beschrijf zo specifiek mogelijk welke informatie u zoekt..."
                className="w-full border border-border-primary rounded-lg px-3 py-2 text-sm text-text-primary placeholder:text-text-placeholder outline-none focus:border-border-brand focus:ring-2 focus:ring-ring-brand-shadow resize-none"
              />
            </div>
            {error && (
              <p className="text-xs text-red-600 bg-red-50 border border-red-100 rounded-lg p-2">{error}</p>
            )}
            <button
              type="submit"
              disabled={loading}
              className="px-4 py-2 bg-bg-brand-solid text-white rounded-lg text-sm font-semibold hover:bg-bg-brand-solid_hover disabled:opacity-50 transition duration-100 ease-linear flex items-center gap-2"
            >
              {loading ? (
                <span className="w-4 h-4 border-2 border-white/30 border-t-white rounded-full animate-spin" />
              ) : (
                <Send01 className="w-4 h-4" />
              )}
              Verzoek indienen
            </button>
          </form>
        </div>
      )}
    </div>
  );
}

/* ─── Organisation Info Card ──────────────────────────────── */

function OrgInfoCard({ org }: { org: BestuursorgaanInfo }) {
  return (
    <div className="rounded-xl border border-border-secondary bg-bg-primary p-5 space-y-4">
      <div className="flex items-center gap-2 mb-1">
        <Building01 className="w-4 h-4 text-fg-brand-primary" />
        <h2 className="text-sm font-semibold text-text-primary">Organisatiegegevens</h2>
      </div>

      {org.beschrijving && (
        <p className="text-sm text-text-tertiary leading-relaxed">{org.beschrijving}</p>
      )}

      <div className="grid grid-cols-1 sm:grid-cols-2 gap-3 text-sm">
        {org.type && (
          <div>
            <span className="text-xs font-medium text-text-quaternary uppercase tracking-wider">Type</span>
            <p className="text-text-secondary mt-0.5">{org.type}</p>
          </div>
        )}
        {org.relatie_ministerie && (
          <div>
            <span className="text-xs font-medium text-text-quaternary uppercase tracking-wider">Ministerie</span>
            <p className="text-text-secondary mt-0.5">{org.relatie_ministerie}</p>
          </div>
        )}
        {org.bezoekadres && (
          <div>
            <span className="text-xs font-medium text-text-quaternary uppercase tracking-wider">Bezoekadres</span>
            <p className="text-text-secondary mt-0.5 flex items-start gap-1.5">
              <MarkerPin01 className="w-3.5 h-3.5 mt-0.5 flex-shrink-0 text-fg-quaternary" />
              {org.bezoekadres}
            </p>
          </div>
        )}
        {org.postadres && (
          <div>
            <span className="text-xs font-medium text-text-quaternary uppercase tracking-wider">Postadres</span>
            <p className="text-text-secondary mt-0.5 flex items-start gap-1.5">
              <Mail01 className="w-3.5 h-3.5 mt-0.5 flex-shrink-0 text-fg-quaternary" />
              {org.postadres}
            </p>
          </div>
        )}
        {org.telefoon && (
          <div>
            <span className="text-xs font-medium text-text-quaternary uppercase tracking-wider">Telefoon</span>
            <p className="text-text-secondary mt-0.5 flex items-center gap-1.5">
              <Phone01 className="w-3.5 h-3.5 flex-shrink-0 text-fg-quaternary" />
              <a href={`tel:${org.telefoon}`} className="hover:text-text-brand-secondary">{org.telefoon}</a>
            </p>
          </div>
        )}
        {org.email && (
          <div>
            <span className="text-xs font-medium text-text-quaternary uppercase tracking-wider">E-mail</span>
            <p className="text-text-secondary mt-0.5 flex items-center gap-1.5">
              <Mail01 className="w-3.5 h-3.5 flex-shrink-0 text-fg-quaternary" />
              <a href={`mailto:${org.email}`} className="hover:text-text-brand-secondary">{org.email}</a>
            </p>
          </div>
        )}
        {org.website && (
          <div className="sm:col-span-2">
            <span className="text-xs font-medium text-text-quaternary uppercase tracking-wider">Website</span>
            <p className="text-text-secondary mt-0.5 flex items-center gap-1.5">
              <Globe02 className="w-3.5 h-3.5 flex-shrink-0 text-fg-quaternary" />
              <a href={org.website} target="_blank" rel="noopener noreferrer" className="hover:text-text-brand-secondary inline-flex items-center gap-1">
                {org.website.replace(/^https?:\/\//, "")}
                <LinkExternal01 className="w-3 h-3" />
              </a>
            </p>
          </div>
        )}
      </div>

      {org.is_woo_plichtig && (
        <div className="flex items-center gap-2 pt-2 border-t border-border-secondary">
          <div className="w-2 h-2 rounded-full bg-green-500" />
          <span className="text-xs font-medium text-green-700">Woo-plichtig bestuursorgaan</span>
        </div>
      )}
    </div>
  );
}

/* ─── Main Page ───────────────────────────────────────────── */

export function OrganisationPage() {
  const { name } = useParams<{ name: string }>();
  const [data, setData] = useState<OrganisationResponse | null>(null);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(false);

  useEffect(() => {
    if (!name) return;
    setLoading(true);
    setError(false);
    api.organisation(name)
      .then(setData)
      .catch(() => setError(true))
      .finally(() => setLoading(false));
  }, [name]);

  if (loading) {
    return (
      <div className="mx-auto max-w-[1187px] px-4 py-10">
        <div className="animate-pulse space-y-6">
          <div className="h-6 bg-bg-secondary rounded w-32" />
          <div className="h-10 bg-bg-secondary rounded w-96" />
          <div className="grid grid-cols-3 gap-4">
            {[1, 2, 3].map((i) => <div key={i} className="h-24 bg-bg-secondary rounded-xl" />)}
          </div>
          <div className="h-64 bg-bg-secondary rounded-xl" />
        </div>
      </div>
    );
  }

  if (error || !data) {
    return (
      <div className="mx-auto max-w-[1187px] px-4 py-10">
        <Link to="/dashboard" className="inline-flex items-center gap-1.5 text-sm text-text-brand-secondary hover:underline mb-6">
          <ArrowLeft className="w-4 h-4" /> Terug naar dashboard
        </Link>
        <div className="text-center py-16">
          <Building01 className="w-10 h-10 text-fg-quaternary mx-auto mb-3" />
          <h2 className="text-lg font-semibold text-text-primary">Organisatie niet gevonden</h2>
          <p className="text-sm text-text-tertiary mt-1">De opgegeven organisatie bestaat niet of heeft geen documenten.</p>
        </div>
      </div>
    );
  }

  const org = data.bestuursorgaan;

  const enrichPct = data.total_documents > 0
    ? ((data.total_enriched / data.total_documents) * 100).toFixed(1)
    : "0";

  const monthLabels = (data.monthly_publications || []).map((m) => {
    const [y, mo] = m.month.split("-");
    return new Date(Number(y), Number(mo) - 1).toLocaleDateString("nl-NL", { month: "short", year: "2-digit" });
  });
  const monthValues = (data.monthly_publications || []).map((m) => m.count);

  return (
    <div className="mx-auto max-w-[1187px] px-4 py-10 animate-fade-in">
      {/* Breadcrumb */}
      <Link to="/dashboard" className="inline-flex items-center gap-1.5 text-sm text-text-brand-secondary hover:underline mb-6">
        <ArrowLeft className="w-4 h-4" /> Dashboard
      </Link>

      {/* Header */}
      <div className="flex items-start gap-4 mb-8">
        {org?.logo_url ? (
          <img src={org.logo_url} alt={data.name} className="w-14 h-14 rounded-xl object-contain border border-border-secondary bg-white" />
        ) : (
          <div className="w-14 h-14 rounded-xl bg-bg-brand-secondary flex items-center justify-center flex-shrink-0">
            <Building01 className="w-7 h-7 text-fg-brand-primary" />
          </div>
        )}
        <div className="min-w-0 flex-1">
          <div className="flex items-start justify-between gap-4">
            <div>
              <h1 className="text-2xl font-bold text-text-primary">{data.name}</h1>
              <div className="flex items-center gap-2 mt-1">
                {org?.type && (
                  <span className="text-xs px-2 py-0.5 rounded-full bg-bg-brand-secondary text-text-brand-secondary font-medium">
                    {org.type}
                  </span>
                )}
                {org?.afkorting && (
                  <span className="text-xs text-text-quaternary">({org.afkorting})</span>
                )}
                {org?.is_woo_plichtig && (
                  <span className="text-xs px-2 py-0.5 rounded-full bg-green-50 text-green-700 font-medium">
                    Woo-plichtig
                  </span>
                )}
              </div>
            </div>
            {org?.website && (
              <a
                href={org.website}
                target="_blank"
                rel="noopener noreferrer"
                className="hidden sm:inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-border-secondary text-sm text-text-secondary hover:bg-bg-primary_hover transition duration-100 ease-linear"
              >
                <Globe02 className="w-4 h-4" />
                Website
                <LinkExternal01 className="w-3 h-3" />
              </a>
            )}
          </div>
        </div>
      </div>

      {/* Subscribe CTA — prominent orange inline CTA */}
      <div className="mb-6">
        <SubscribeCTA organisationName={data.name} />
      </div>

      {/* Stat cards */}
      <div className="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
        <div className="p-5 rounded-xl border border-border-secondary bg-bg-primary">
          <div className="flex items-center gap-3 mb-2">
            <File06 className="w-4 h-4 text-fg-brand-primary" />
            <span className="text-sm font-medium text-text-tertiary">Documenten</span>
          </div>
          <p className="text-2xl font-bold text-text-primary">{data.total_documents.toLocaleString("nl-NL")}</p>
        </div>
        <div className="p-5 rounded-xl border border-border-secondary bg-bg-primary">
          <div className="flex items-center gap-3 mb-2">
            <Zap className="w-4 h-4 text-fg-brand-primary" />
            <span className="text-sm font-medium text-text-tertiary">AI Verrijkt</span>
          </div>
          <p className="text-2xl font-bold text-text-primary">{data.total_enriched.toLocaleString("nl-NL")}</p>
          <p className="text-xs text-text-quaternary">{enrichPct}% van totaal</p>
        </div>
        <div className="p-5 rounded-xl border border-border-secondary bg-bg-primary">
          <div className="flex items-center gap-3 mb-2">
            <Tag01 className="w-4 h-4 text-fg-brand-primary" />
            <span className="text-sm font-medium text-text-tertiary">Thema's</span>
          </div>
          <p className="text-2xl font-bold text-text-primary">{data.themes.length}</p>
        </div>
      </div>

      {/* Two-column: Org info + WOO verzoek */}
      <div className="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        {org && <OrgInfoCard org={org} />}

        <div className="space-y-6">
          {/* WOO Verzoek */}
          {(org?.is_woo_plichtig || org?.woo_email) && (
            <WooVerzoekForm
              organisationName={data.name}
              wooEmail={org?.woo_email}
              wooAdres={org?.woo_adres}
            />
          )}

          {/* Quick actions */}
          <div className="rounded-xl border border-border-secondary bg-bg-primary p-5">
            <h2 className="text-sm font-semibold text-text-primary mb-3">Snelle acties</h2>
            <div className="space-y-2">
              <Link
                to={`/zoeken?q=&organisation=${encodeURIComponent(data.name)}`}
                className="flex items-center gap-3 p-2.5 rounded-lg hover:bg-bg-primary_hover transition duration-100 ease-linear group"
              >
                <File06 className="w-4 h-4 text-fg-quaternary group-hover:text-fg-brand-primary" />
                <span className="text-sm text-text-secondary">Alle documenten doorzoeken</span>
                <ArrowRight className="w-3.5 h-3.5 text-fg-quaternary ml-auto opacity-0 group-hover:opacity-100 transition" />
              </Link>
              <Link
                to={`/chat`}
                className="flex items-center gap-3 p-2.5 rounded-lg hover:bg-bg-primary_hover transition duration-100 ease-linear group"
              >
                <Zap className="w-4 h-4 text-fg-quaternary group-hover:text-fg-brand-primary" />
                <span className="text-sm text-text-secondary">AI-chat over deze organisatie</span>
                <ArrowRight className="w-3.5 h-3.5 text-fg-quaternary ml-auto opacity-0 group-hover:opacity-100 transition" />
              </Link>
              {org?.contactformulier_url && (
                <a
                  href={org.contactformulier_url}
                  target="_blank"
                  rel="noopener noreferrer"
                  className="flex items-center gap-3 p-2.5 rounded-lg hover:bg-bg-primary_hover transition duration-100 ease-linear group"
                >
                  <Send01 className="w-4 h-4 text-fg-quaternary group-hover:text-fg-brand-primary" />
                  <span className="text-sm text-text-secondary">Contact opnemen</span>
                  <LinkExternal01 className="w-3.5 h-3.5 text-fg-quaternary ml-auto opacity-0 group-hover:opacity-100 transition" />
                </a>
              )}
            </div>
          </div>
        </div>
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

      {/* Themes & Categories charts */}
      <div className="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        {data.themes.length > 0 && (
          <div className="rounded-xl border border-border-secondary p-5">
            <div className="flex items-center gap-2 mb-2">
              <Tag01 className="w-4 h-4 text-fg-brand-primary" />
              <h2 className="text-sm font-semibold text-text-primary">Thema's</h2>
            </div>
            <HorizontalBar
              categories={data.themes.map((t) => t.theme)}
              series={data.themes.map((t) => t.count)}
              color="#7A5AF8"
            />
          </div>
        )}

        {data.categories.length > 0 && (
          <div className="rounded-xl border border-border-secondary p-5">
            <div className="flex items-center gap-2 mb-2">
              <File06 className="w-4 h-4 text-fg-brand-primary" />
              <h2 className="text-sm font-semibold text-text-primary">Categorieën</h2>
            </div>
            <HorizontalBar
              categories={data.categories.map((c) => c.category)}
              series={data.categories.map((c) => c.count)}
              color="#12B76A"
            />
          </div>
        )}
      </div>

      {/* Recent documents */}
      {data.recent_documents.length > 0 && (
        <div className="rounded-xl border border-border-secondary p-5">
          <div className="flex items-center justify-between mb-4">
            <h2 className="text-sm font-semibold text-text-primary">Recente documenten</h2>
            <Link
              to={`/zoeken?q=&organisation=${encodeURIComponent(data.name)}`}
              className="text-xs text-text-brand-secondary hover:underline inline-flex items-center gap-1"
            >
              Alle documenten <ArrowRight className="w-3 h-3" />
            </Link>
          </div>
          <div className="space-y-3">
            {data.recent_documents.map((doc) => (
              <Link
                key={doc.external_id}
                to={`/open-overheid/documents/${doc.external_id}`}
                className="block p-3 rounded-lg border border-border-secondary hover:border-border-brand hover:bg-bg-primary_hover transition duration-100 ease-linear"
              >
                <h3 className="text-sm font-medium text-text-primary line-clamp-2">{doc.title}</h3>
                {doc.description && (
                  <p className="text-xs text-text-tertiary mt-1 line-clamp-2">{doc.description}</p>
                )}
                <div className="flex items-center gap-3 mt-2">
                  {doc.publication_date && (
                    <span className="inline-flex items-center gap-1 text-xs text-text-quaternary">
                      <Calendar className="w-3 h-3" />
                      {new Date(doc.publication_date).toLocaleDateString("nl-NL")}
                    </span>
                  )}
                  {doc.theme && (
                    <span className="text-xs text-text-quaternary">{doc.theme}</span>
                  )}
                  {doc.category && (
                    <span className="text-xs px-1.5 py-0.5 rounded bg-bg-secondary text-text-quaternary">{doc.category}</span>
                  )}
                </div>
              </Link>
            ))}
          </div>
        </div>
      )}

      {/* Claim CTA for organisations */}
      {org && !org.is_claimed && (
        <div className="mt-6 rounded-xl border border-dashed border-border-primary bg-bg-secondary p-5 text-center">
          <Building01 className="w-8 h-8 text-fg-quaternary mx-auto mb-2" />
          <h3 className="text-sm font-semibold text-text-primary">Bent u van {data.name}?</h3>
          <p className="text-xs text-text-tertiary mt-1 max-w-md mx-auto">
            Claim deze organisatiepagina om uw logo, beschrijving en contactgegevens te beheren en te verrijken.
          </p>
          <Link
            to={`/inloggen?redirect=/organisaties/${encodeURIComponent(data.name)}&claim=true`}
            className="inline-flex items-center gap-2 mt-3 px-4 py-2 bg-bg-brand-solid text-white rounded-lg text-sm font-semibold hover:bg-bg-brand-solid_hover transition duration-100 ease-linear"
          >
            <Building01 className="w-4 h-4" />
            Pagina claimen
          </Link>
        </div>
      )}
    </div>
  );
}
