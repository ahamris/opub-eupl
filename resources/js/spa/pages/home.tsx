import { Link } from "react-router";
import { SearchLg, MessageChatCircle, File06, Zap, ChevronRight, ArrowRight, BarChart01, Bell01, Globe01, Shield01, Lock01, Code01 } from "@untitledui/icons";
import { ParticleBg } from "../components/features/particle-bg";
import { CommandPalette } from "../components/features/command-palette";

const LABEL_COLORS: Record<string, string> = {
  "Woo-verzoeken": "bg-amber-50 text-amber-700 border-amber-200",
  "Besluiten": "bg-blue-50 text-blue-700 border-blue-200",
  "Kamerstukken": "bg-emerald-50 text-emerald-700 border-emerald-200",
  "Rapporten": "bg-purple-50 text-purple-700 border-purple-200",
  "Vergunningen": "bg-rose-50 text-rose-700 border-rose-200",
  "Beleid": "bg-cyan-50 text-cyan-700 border-cyan-200",
};

const TRENDING = [
  { label: "Woo-verzoeken", q: "Woo-verzoek" },
  { label: "Besluiten", q: "besluit gemeenteraad" },
  { label: "Kamerstukken", q: "kamerstukken motie" },
  { label: "Vergunningen", q: "omgevingsvergunning" },
  { label: "Rapporten", q: "onderzoeksrapport" },
  { label: "Beleid", q: "beleidsregel" },
];

const FEATURES = [
  {
    icon: SearchLg, title: "Federatief zoekportaal",
    desc: "Doorzoek alle actief openbaar gemaakte overheidsdocumenten via één zoekinterface. Filter op organisatie, documenttype, datum en thema.",
    bg: "bg-blue-50", iconColor: "text-blue-600",
  },
  {
    icon: MessageChatCircle, title: "AI-chat (sovereign AI)",
    desc: "Stel vragen in gewone taal aan 641.000+ documenten. Volledig lokaal via Ollama en het Nederlandse Geitje-taalmodel. Geen externe cloudproviders.",
    bg: "bg-violet-50", iconColor: "text-violet-600",
  },
  {
    icon: Bell01, title: "Attendering",
    desc: "Stel een attendering in op een zoekterm, organisatie of thema. Bij nieuwe publicaties ontvangt u automatisch een melding.",
    bg: "bg-amber-50", iconColor: "text-amber-600",
  },
  {
    icon: Globe01, title: "Buffer-API (Generieke Woo API)",
    desc: "Bestuursorganen leveren documenten aan via de open API. oPub verzendt automatisch door naar de Generieke Woo-voorziening en open.overheid.nl.",
    bg: "bg-emerald-50", iconColor: "text-emerald-600",
  },
  {
    icon: BarChart01, title: "Dashboarding",
    desc: "Inzicht in publicatiestromen: volumes, documentcategorieën, publicatiefrequentie en Woo-compliance-indicatoren per organisatie.",
    bg: "bg-cyan-50", iconColor: "text-cyan-600",
  },
  {
    icon: Code01, title: "Open source (EUPL 1.2)",
    desc: "Volledige broncode beschikbaar op GitHub onder EUPL 1.2. Geen vendor lock-in. Afgeleiden blijven open source. Gemaakt door CodeLabs B.V.",
    bg: "bg-gray-100", iconColor: "text-gray-600",
  },
];

const AUDIENCES = [
  {
    title: "Burger",
    desc: "Vind elk openbaar gemaakt overheidsdocument in één zoekopdracht.",
    cta: "Zoek in documenten",
    to: "/zoeken",
    color: "border-blue-200 bg-blue-50/50",
    ctaColor: "text-blue-700",
  },
  {
    title: "Journalist & onderzoeker",
    desc: "Doorzoek 641.000+ documenten. Stel vragen aan de AI. Stel attenderingen in.",
    cta: "Start AI-chat",
    to: "/chat",
    color: "border-violet-200 bg-violet-50/50",
    ctaColor: "text-violet-700",
  },
  {
    title: "Bestuursorgaan",
    desc: "Sluit uw organisatie gratis aan. Voldoe aan uw Woo-publicatieplicht. Geen licentiekosten.",
    cta: "Aansluiten",
    to: "/contact",
    color: "border-emerald-200 bg-emerald-50/50",
    ctaColor: "text-emerald-700",
  },
];

const ARTICLES = [
  { title: "Wat is de Wet Open Overheid?", desc: "De Woo maakt overheidsinformatie toegankelijker. Wat zijn uw rechten als burger?", tag: "Woo-uitleg", tagColor: "bg-blue-50 text-blue-700", img: "https://images.unsplash.com/photo-1589829545856-d10d557cf95f?w=400&h=250&fit=crop", url: "/kennisbank" },
  { title: "Hoe sluit ik aan als bestuursorgaan?", desc: "Kosteloos aansluiten via de open API of het uploadportaal. Stap voor stap.", tag: "Handleiding", tagColor: "bg-emerald-50 text-emerald-700", img: "https://images.unsplash.com/photo-1586281380349-632531db7ed4?w=400&h=250&fit=crop", url: "/kennisbank" },
  { title: "Sovereign AI: Ollama + Geitje", desc: "Alle AI-verwerking lokaal. Geen externe cloud. Volledige controle over data.", tag: "Technologie", tagColor: "bg-violet-50 text-violet-700", img: "https://images.unsplash.com/photo-1677442136019-21780ecad995?w=400&h=250&fit=crop", url: "/kennisbank" },
  { title: "Open API documentatie", desc: "Integreer de oPub zoek-, chat- en ingest-API in uw eigen systemen.", tag: "Developer", tagColor: "bg-amber-50 text-amber-700", img: "https://images.unsplash.com/photo-1555066931-4365d14bab8c?w=400&h=250&fit=crop", url: "/api/docs" },
];

export function HomePage() {
  return (
    <div className="flex flex-col">
      {/* ── Hero ── */}
      <section className="relative bg-gradient-to-b from-[#f8faff] via-white to-gray-50 min-h-[520px] flex items-center" style={{ zIndex: 50, overflow: "visible" }} >
        <ParticleBg className="opacity-40" />
        <div className="relative mx-auto max-w-[1187px] px-4 py-16 sm:py-20 text-center w-full">
          <div className="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-gradient-to-r from-blue-50 to-violet-50 border border-blue-100 text-xs font-medium mb-6">
            <span className="flex h-2 w-2 rounded-full bg-emerald-500 animate-pulse" />
            <span className="text-blue-700">Open source</span>
            <span className="text-gray-300">·</span>
            <span className="text-violet-700">Sovereign AI</span>
            <span className="text-gray-300">·</span>
            <span className="text-emerald-700">EUPL 1.2</span>
          </div>

          <h1 className="text-4xl sm:text-5xl lg:text-[52px] font-bold text-gray-900 tracking-tight leading-[1.08]">
            Vind elk openbaar gemaakt<br />
            <span className="bg-gradient-to-r from-blue-600 to-violet-600 bg-clip-text text-transparent">
              overheidsdocument
            </span>
          </h1>

          <p className="mt-4 text-base sm:text-lg text-gray-500 max-w-lg mx-auto leading-relaxed">
            Direct. Gratis. Open. Doorzoek 641.000+ Woo-publicaties
            van alle aangesloten bestuursorganen — met sovereign AI.
          </p>

          <div className="mt-8">
            <CommandPalette />
          </div>

          <div className="flex flex-wrap items-center justify-center gap-2 mt-6">
            <span className="text-xs text-gray-400 mr-1">Populair:</span>
            {TRENDING.map(({ label, q }) => (
              <Link key={label} to={`/zoeken?q=${encodeURIComponent(q)}`}
                className={`px-2.5 py-1 rounded-full text-[11px] font-medium border transition-all hover:shadow-sm ${LABEL_COLORS[label] || "bg-gray-50 text-gray-600 border-gray-200"}`}>
                {label}
              </Link>
            ))}
          </div>
        </div>
      </section>

      {/* ── Stats ── */}
      <section className="border-y border-gray-100 bg-white relative">
        <div className="mx-auto max-w-[1187px] px-4 py-5">
          <div className="grid grid-cols-2 sm:grid-cols-4 gap-6">
            {[
              { icon: File06, value: "641.000+", label: "Woo-documenten", color: "text-blue-600" },
              { icon: Shield01, value: "Sovereign", label: "AI (Ollama + Geitje)", color: "text-violet-600" },
              { icon: Lock01, value: "EUPL 1.2", label: "Open source", color: "text-emerald-600" },
              { icon: Globe01, value: "~2.000", label: "Bestuursorganen", color: "text-amber-600" },
            ].map(({ icon: Icon, value, label, color }) => (
              <div key={label} className="flex items-center gap-3">
                <Icon className={`w-5 h-5 ${color}`} />
                <div>
                  <p className="text-lg font-bold text-gray-900">{value}</p>
                  <p className="text-xs text-gray-500">{label}</p>
                </div>
              </div>
            ))}
          </div>
        </div>
      </section>

      {/* ── Features (6 grid) ── */}
      <section className="py-20 px-4 bg-white relative">
        <div className="mx-auto max-w-[1187px]">
          <div className="text-center mb-12">
            <span className="inline-block px-3 py-1 rounded-full bg-blue-50 text-blue-700 text-xs font-semibold mb-3">Functies</span>
            <h2 className="text-2xl sm:text-3xl font-bold text-gray-900">Open source Woo-voorziening voor de hele overheid</h2>
            <p className="text-base text-gray-500 mt-2 max-w-lg mx-auto">Kosteloos voor alle ~2.000 Woo-plichtige bestuursorganen. Geen licentiekosten, geen vendor lock-in.</p>
          </div>
          <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
            {FEATURES.map(({ icon: Icon, title, desc, bg, iconColor }) => (
              <div key={title} className="p-6 rounded-2xl border border-gray-100 hover:border-gray-200 hover:shadow-md transition-all bg-white">
                <div className={`w-11 h-11 rounded-xl ${bg} flex items-center justify-center mb-4`}>
                  <Icon className={`w-5 h-5 ${iconColor}`} />
                </div>
                <h3 className="text-base font-semibold text-gray-900 mb-2">{title}</h3>
                <p className="text-sm text-gray-500 leading-relaxed">{desc}</p>
              </div>
            ))}
          </div>
        </div>
      </section>

      {/* ── Doelgroepen ── */}
      <section className="py-16 px-4 bg-gray-50">
        <div className="mx-auto max-w-[1187px]">
          <div className="text-center mb-10">
            <span className="inline-block px-3 py-1 rounded-full bg-violet-50 text-violet-700 text-xs font-semibold mb-3">Voor wie?</span>
            <h2 className="text-2xl font-bold text-gray-900">Eén platform, drie doelgroepen</h2>
          </div>
          <div className="grid grid-cols-1 md:grid-cols-3 gap-5">
            {AUDIENCES.map(({ title, desc, cta, to, color, ctaColor }) => (
              <div key={title} className={`p-6 rounded-2xl border ${color} transition-all hover:shadow-md`}>
                <h3 className="text-lg font-semibold text-gray-900 mb-2">{title}</h3>
                <p className="text-sm text-gray-600 leading-relaxed mb-4">{desc}</p>
                <Link to={to} className={`inline-flex items-center gap-1.5 text-sm font-semibold ${ctaColor} hover:underline`}>
                  {cta} <ArrowRight className="w-3.5 h-3.5" />
                </Link>
              </div>
            ))}
          </div>
        </div>
      </section>

      {/* ── CTA — Sovereign AI ── */}
      <section className="py-16 px-4 bg-white">
        <div className="mx-auto max-w-[1187px] rounded-3xl bg-gradient-to-br from-blue-600 via-blue-700 to-violet-700 p-10 sm:p-14 relative overflow-hidden">
          <div className="absolute top-0 right-0 w-96 h-96 bg-white/5 rounded-full -translate-y-1/2 translate-x-1/3" />
          <div className="absolute bottom-0 left-0 w-64 h-64 bg-white/5 rounded-full translate-y-1/2 -translate-x-1/3" />
          <div className="relative flex flex-col sm:flex-row items-center gap-8">
            <div className="flex-1 text-center sm:text-left">
              <span className="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-white/10 text-white/90 text-[11px] font-medium mb-4 border border-white/20">
                <Shield01 className="w-3 h-3" /> Sovereign AI
              </span>
              <h2 className="text-2xl sm:text-3xl font-bold text-white mb-3">
                Stel vragen aan de AI-assistent
              </h2>
              <p className="text-blue-100 text-sm sm:text-base max-w-md leading-relaxed">
                Uw zoekvraag en de documenten verlaten nooit onze eigen infrastructuur. Lokaal verwerkt via Ollama en het Nederlandse Geitje-taalmodel. Geen externe AI-providers.
              </p>
            </div>
            <div className="flex flex-col gap-3 shrink-0">
              <Link to="/chat" className="inline-flex items-center justify-center gap-2 px-6 py-3 bg-white text-blue-700 rounded-xl text-sm font-semibold hover:bg-blue-50 transition-colors shadow-lg">
                <MessageChatCircle className="w-4 h-4" /> Start een gesprek
              </Link>
              <Link to="/contact" className="inline-flex items-center justify-center gap-2 px-6 py-3 bg-white/10 text-white rounded-xl text-sm font-medium hover:bg-white/20 transition-colors border border-white/20">
                Sluit uw organisatie aan <ArrowRight className="w-4 h-4" />
              </Link>
            </div>
          </div>
        </div>
      </section>

      {/* ── Kennisbank ── */}
      <section className="py-20 px-4 bg-gray-50">
        <div className="mx-auto max-w-[1187px]">
          <div className="flex items-end justify-between mb-8">
            <div>
              <span className="inline-block px-3 py-1 rounded-full bg-amber-50 text-amber-700 text-xs font-semibold mb-3">Kennisbank</span>
              <h2 className="text-2xl font-bold text-gray-900">Artikelen & documentatie</h2>
            </div>
            <Link to="/kennisbank" className="hidden sm:flex items-center gap-1 text-sm font-medium text-blue-600 hover:text-blue-700">
              Alles bekijken <ChevronRight className="w-4 h-4" />
            </Link>
          </div>
          <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
            {ARTICLES.map((a) => (
              <Link key={a.title} to={a.url}
                className="flex flex-col rounded-2xl border border-gray-100 overflow-hidden hover:shadow-lg hover:border-gray-200 transition-all group bg-white">
                <div className="relative h-36 overflow-hidden bg-gray-100">
                  <img src={a.img} alt="" className="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" loading="lazy" />
                  <div className="absolute top-3 left-3">
                    <span className={`px-2.5 py-1 rounded-full text-[10px] font-semibold ${a.tagColor}`}>{a.tag}</span>
                  </div>
                </div>
                <div className="p-4 flex-1 flex flex-col">
                  <h3 className="text-sm font-semibold text-gray-900 group-hover:text-blue-600 transition-colors mb-1.5 line-clamp-2">{a.title}</h3>
                  <p className="text-xs text-gray-500 leading-relaxed flex-1 line-clamp-3">{a.desc}</p>
                  <span className="flex items-center gap-1 text-xs font-medium text-blue-600 mt-3 group-hover:gap-2 transition-all">
                    Lees meer <ArrowRight className="w-3 h-3" />
                  </span>
                </div>
              </Link>
            ))}
          </div>
        </div>
      </section>

      {/* ── Over oPub strip ── */}
      <section className="py-12 px-4 bg-white border-t border-gray-100">
        <div className="mx-auto max-w-[1187px] text-center">
          <p className="text-sm text-gray-500 leading-relaxed">
            <strong className="text-gray-700">oPub</strong> is een volledig open source Woo-zoekplatform waarop alle bestuursorganen kosteloos kunnen aansluiten.
            Gemaakt door <strong className="text-gray-700">CodeLabs B.V.</strong> · Gepubliceerd op <a href="https://developer.overheid.nl" target="_blank" rel="noopener" className="text-blue-600 hover:underline">developer.overheid.nl</a> · Licentie: <strong className="text-gray-700">EUPL 1.2</strong>
          </p>
        </div>
      </section>
    </div>
  );
}
