import { useState } from "react";
import { Send01, Mail01, Phone01, MarkerPin01, Building01, Globe01, ArrowRight, Check, Clock } from "@untitledui/icons";

const SUBJECTS = [
  "Algemene vraag",
  "Aansluiten als bestuursorgaan",
  "Demo aanvragen",
  "Technische vraag / API",
  "Bug melden",
  "Overig",
];

export function ContactPage() {
  const [form, setForm] = useState({ name: "", email: "", organisation: "", subject: SUBJECTS[0], message: "" });
  const [status, setStatus] = useState<"idle" | "sending" | "sent" | "error">("idle");

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    setStatus("sending");
    try {
      const csrf = document.querySelector<HTMLMetaElement>('meta[name="csrf-token"]')?.content;
      const res = await fetch("/contact", {
        method: "POST",
        headers: { "Content-Type": "application/json", Accept: "application/json", ...(csrf ? { "X-CSRF-TOKEN": csrf } : {}) },
        credentials: "same-origin",
        body: JSON.stringify(form),
      });
      setStatus(res.ok ? "sent" : "error");
      if (res.ok) setForm({ name: "", email: "", organisation: "", subject: SUBJECTS[0], message: "" });
    } catch { setStatus("error"); }
  };

  const inputCls = "w-full px-3.5 py-2.5 border border-gray-200 rounded-lg text-sm text-gray-900 placeholder:text-gray-400 focus:outline-none focus:border-blue-300 focus:ring-4 focus:ring-blue-50 transition-all bg-white";

  return (
    <div className="mx-auto max-w-[1187px] px-4 sm:px-6 py-12">
      {/* Header */}
      <div className="text-center mb-12">
        <span className="inline-block px-3 py-1 rounded-full bg-blue-50 text-blue-700 text-xs font-semibold mb-3">Contact</span>
        <h1 className="text-2xl sm:text-3xl font-bold text-gray-900">Neem contact op</h1>
        <p className="text-base text-gray-500 mt-2 max-w-lg mx-auto">
          Vragen over oPub, aansluiten als bestuursorgaan, of technische ondersteuning? Wij helpen u graag.
        </p>
      </div>

      <div className="grid grid-cols-1 lg:grid-cols-5 gap-10">
        {/* Linker kolom — Formulier */}
        <div className="lg:col-span-3">
          {status === "sent" ? (
            <div className="rounded-2xl border border-emerald-200 bg-emerald-50 p-10 text-center">
              <div className="w-12 h-12 rounded-full bg-emerald-100 flex items-center justify-center mx-auto mb-4">
                <Check className="w-6 h-6 text-emerald-600" />
              </div>
              <h2 className="text-lg font-semibold text-gray-900 mb-1">Bericht verzonden</h2>
              <p className="text-sm text-gray-600">We nemen binnen 2 werkdagen contact met u op.</p>
              <button onClick={() => setStatus("idle")} className="mt-4 text-sm font-medium text-blue-600 hover:underline">
                Nog een bericht sturen
              </button>
            </div>
          ) : (
            <div className="rounded-2xl border border-gray-100 bg-white p-6 sm:p-8">
              <h2 className="text-lg font-semibold text-gray-900 mb-1">Stuur ons een bericht</h2>
              <p className="text-sm text-gray-500 mb-6">Alle velden met * zijn verplicht.</p>

              <form onSubmit={handleSubmit} className="space-y-5">
                <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
                  <div>
                    <label className="block text-sm font-medium text-gray-700 mb-1.5">Naam *</label>
                    <input type="text" required value={form.name} onChange={(e) => setForm({ ...form, name: e.target.value })} placeholder="Uw naam" className={inputCls} />
                  </div>
                  <div>
                    <label className="block text-sm font-medium text-gray-700 mb-1.5">E-mail *</label>
                    <input type="email" required value={form.email} onChange={(e) => setForm({ ...form, email: e.target.value })} placeholder="u@organisatie.nl" className={inputCls} />
                  </div>
                </div>

                <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
                  <div>
                    <label className="block text-sm font-medium text-gray-700 mb-1.5">Organisatie</label>
                    <input type="text" value={form.organisation} onChange={(e) => setForm({ ...form, organisation: e.target.value })} placeholder="Gemeente, ministerie, etc." className={inputCls} />
                  </div>
                  <div>
                    <label className="block text-sm font-medium text-gray-700 mb-1.5">Onderwerp *</label>
                    <select value={form.subject} onChange={(e) => setForm({ ...form, subject: e.target.value })} className={inputCls}>
                      {SUBJECTS.map((s) => <option key={s} value={s}>{s}</option>)}
                    </select>
                  </div>
                </div>

                <div>
                  <label className="block text-sm font-medium text-gray-700 mb-1.5">Bericht *</label>
                  <textarea required rows={5} value={form.message} onChange={(e) => setForm({ ...form, message: e.target.value })} placeholder="Beschrijf uw vraag of verzoek..." className={`${inputCls} resize-none`} />
                </div>

                {status === "error" && <p className="text-sm text-red-600">Er ging iets mis. Probeer het opnieuw.</p>}

                <button type="submit" disabled={status === "sending"}
                  className="w-full flex items-center justify-center gap-2 px-4 py-2.5 bg-blue-600 text-white rounded-lg text-sm font-semibold hover:bg-blue-700 disabled:opacity-50 transition-colors">
                  <Send01 className="w-4 h-4" />
                  {status === "sending" ? "Verzenden..." : "Verstuur bericht"}
                </button>
              </form>
            </div>
          )}
        </div>

        {/* Rechter kolom — Contactgegevens */}
        <div className="lg:col-span-2 space-y-6">
          {/* Aansluiten CTA */}
          <div className="rounded-2xl bg-gradient-to-br from-blue-600 to-violet-600 p-6 text-white">
            <h3 className="text-base font-semibold mb-2">Aansluiten als bestuursorgaan?</h3>
            <p className="text-sm text-blue-100 leading-relaxed mb-4">
              Elk bestuursorgaan kan kosteloos aansluiten op oPub. Geen licentiekosten, geen contracten. Lever documenten aan via de open API of het uploadportaal.
            </p>
            <a href="mailto:info@codelabs.nl?subject=Aansluiten%20op%20oPub" className="inline-flex items-center gap-1.5 px-4 py-2 bg-white text-blue-700 rounded-lg text-sm font-semibold hover:bg-blue-50 transition-colors">
              Neem contact op <ArrowRight className="w-3.5 h-3.5" />
            </a>
          </div>

          {/* Bedrijfsgegevens */}
          <div className="rounded-2xl border border-gray-100 bg-white p-6 space-y-5">
            <div>
              <h3 className="text-sm font-semibold text-gray-900 mb-3">CodeLabs B.V.</h3>
              <p className="text-xs text-gray-500 leading-relaxed">
                Maker en open source steward van oPub — OpenPublicaties. Gepubliceerd op developer.overheid.nl. Licentie: EUPL 1.2.
              </p>
            </div>

            <div className="space-y-3">
              {[
                { icon: MarkerPin01, label: "Adres", value: "Kamperingweg 45C", sub: "2803 PE Gouda, Nederland" },
                { icon: Mail01, label: "E-mail", value: "info@codelabs.nl", href: "mailto:info@codelabs.nl" },
                { icon: Phone01, label: "Telefoon", value: "+31 (0)85 212 9557", href: "tel:+31852129557" },
                { icon: Globe01, label: "Website", value: "code-labs.nl", href: "https://code-labs.nl" },
                { icon: Clock, label: "Bereikbaar", value: "Ma–Vr 09:00–17:00" },
              ].map(({ icon: Icon, label, value, sub, href }) => (
                <div key={label} className="flex items-start gap-3">
                  <div className="w-8 h-8 rounded-lg bg-gray-50 flex items-center justify-center shrink-0">
                    <Icon className="w-4 h-4 text-gray-400" />
                  </div>
                  <div>
                    <p className="text-[11px] text-gray-400 font-medium">{label}</p>
                    {href ? (
                      <a href={href} className="text-sm text-gray-900 hover:text-blue-600 transition-colors" target={href.startsWith("http") ? "_blank" : undefined} rel={href.startsWith("http") ? "noopener" : undefined}>{value}</a>
                    ) : (
                      <p className="text-sm text-gray-900">{value}</p>
                    )}
                    {sub && <p className="text-xs text-gray-500">{sub}</p>}
                  </div>
                </div>
              ))}
            </div>
          </div>

          {/* Snellinks */}
          <div className="rounded-2xl border border-gray-100 bg-white p-6">
            <h3 className="text-sm font-semibold text-gray-900 mb-3">Snellinks</h3>
            <div className="space-y-2">
              {[
                { label: "API Documentatie (Swagger)", to: "/api/docs" },
                { label: "GitHub (EUPL 1.2)", href: "https://github.com/code-labs-nl" },
                { label: "developer.overheid.nl", href: "https://developer.overheid.nl" },
                { label: "Kennisbank", to: "/kennisbank" },
              ].map((link) => (
                <a key={link.label}
                  href={'href' in link ? link.href : link.to}
                  target={'href' in link ? "_blank" : undefined}
                  rel={'href' in link ? "noopener" : undefined}
                  className="flex items-center justify-between py-1.5 px-2 rounded-md text-sm text-gray-600 hover:text-blue-600 hover:bg-gray-50 transition-colors group">
                  {link.label}
                  <ArrowRight className="w-3 h-3 text-gray-300 group-hover:text-blue-500" />
                </a>
              ))}
            </div>
          </div>
        </div>
      </div>
    </div>
  );
}
