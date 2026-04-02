import { useState } from "react";
import { Bell01, XClose, Check, Mail01 } from "@untitledui/icons";

interface SubscribeModalProps {
  open: boolean;
  onClose: () => void;
  searchQuery: string;
  activeFilters: Record<string, string[]>;
}

const FREQUENCIES = [
  { value: "daily", label: "Dagelijks", desc: "Eenmaal per dag een overzicht" },
  { value: "weekly", label: "Wekelijks", desc: "Eenmaal per week een overzicht" },
  { value: "immediate", label: "Direct", desc: "Zodra er nieuwe documenten zijn" },
] as const;

export function SubscribeModal({ open, onClose, searchQuery, activeFilters }: SubscribeModalProps) {
  const [email, setEmail] = useState("");
  const [frequency, setFrequency] = useState<string>("daily");
  const [loading, setLoading] = useState(false);
  const [success, setSuccess] = useState(false);
  const [error, setError] = useState("");

  if (!open) return null;

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    if (!email.trim()) return;

    setLoading(true);
    setError("");

    // Build filters object for API
    const filters: Record<string, string> = {};
    for (const [key, values] of Object.entries(activeFilters)) {
      if (values.length > 0) filters[key] = values.join("|");
    }

    try {
      const csrf = document.querySelector<HTMLMetaElement>('meta[name="csrf-token"]')?.content || "";
      const res = await fetch("/api/subscriptions", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
          Accept: "application/json",
          "X-CSRF-TOKEN": csrf,
        },
        credentials: "same-origin",
        body: JSON.stringify({
          email: email.trim(),
          frequency,
          search_query: searchQuery || null,
          filters: Object.keys(filters).length > 0 ? filters : null,
        }),
      });

      if (res.status === 409) {
        setError("U heeft al een actieve attendering voor deze zoekopdracht.");
        return;
      }

      if (!res.ok) {
        const data = await res.json().catch(() => ({}));
        throw new Error(data.message || "Er ging iets mis.");
      }

      setSuccess(true);
    } catch (err: any) {
      setError(err.message || "Er ging iets mis. Probeer het opnieuw.");
    } finally {
      setLoading(false);
    }
  };

  const filterSummary = Object.entries(activeFilters)
    .filter(([, v]) => v.length > 0)
    .map(([k, v]) => v.join(", "))
    .join(" · ");

  return (
    <div className="fixed inset-0 z-50 flex items-center justify-center p-4">
      {/* Backdrop */}
      <div className="absolute inset-0 bg-black/20 backdrop-blur-sm" onClick={onClose} />

      {/* Modal */}
      <div className="relative bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden">
        {/* Header — orange gradient */}
        <div className="bg-gradient-to-r from-orange-500 to-amber-500 px-6 py-5">
          <div className="flex items-center justify-between">
            <div className="flex items-center gap-3">
              <div className="w-9 h-9 rounded-xl bg-white/20 flex items-center justify-center">
                <Bell01 className="w-5 h-5 text-white" />
              </div>
              <div>
                <h2 className="text-base font-semibold text-white">Attendering instellen</h2>
                <p className="text-xs text-orange-100 mt-0.5">Ontvang meldingen bij nieuwe publicaties</p>
              </div>
            </div>
            <button onClick={onClose} className="p-1.5 rounded-lg text-white/60 hover:text-white hover:bg-white/10 transition-colors">
              <XClose className="w-4 h-4" />
            </button>
          </div>
        </div>

        {success ? (
          /* Success state */
          <div className="p-6 text-center">
            <div className="w-12 h-12 rounded-full bg-emerald-100 flex items-center justify-center mx-auto mb-3">
              <Check className="w-6 h-6 text-emerald-600" />
            </div>
            <h3 className="text-base font-semibold text-gray-900 mb-1">Attendering aangemaakt</h3>
            <p className="text-sm text-gray-500 mb-4">
              We hebben een bevestigingsmail gestuurd naar <strong className="text-gray-700">{email}</strong>.
              Klik op de link in de e-mail om uw attendering te activeren.
            </p>
            <button onClick={onClose} className="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-200 transition-colors">
              Sluiten
            </button>
          </div>
        ) : (
          /* Form */
          <form onSubmit={handleSubmit} className="p-6 space-y-4">
            {/* What you're subscribing to */}
            <div className="rounded-lg bg-gray-50 border border-gray-100 p-3">
              <p className="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Attendering voor</p>
              <p className="text-sm font-medium text-gray-900">
                {searchQuery ? `"${searchQuery}"` : "Alle documenten"}
              </p>
              {filterSummary && (
                <p className="text-xs text-gray-500 mt-0.5">{filterSummary}</p>
              )}
            </div>

            {/* Email */}
            <div>
              <label className="text-sm font-medium text-gray-700 block mb-1.5">E-mailadres</label>
              <div className="flex items-center gap-2 border border-gray-200 rounded-lg px-3 py-2 focus-within:border-orange-300 focus-within:ring-2 focus-within:ring-orange-50 transition-all">
                <Mail01 className="w-4 h-4 text-gray-400 shrink-0" />
                <input
                  type="email"
                  value={email}
                  onChange={(e) => setEmail(e.target.value)}
                  placeholder="uw@email.nl"
                  required
                  className="flex-1 bg-transparent border-0 outline-none text-sm text-gray-900 placeholder:text-gray-400"
                />
              </div>
            </div>

            {/* Frequency */}
            <div>
              <label className="text-sm font-medium text-gray-700 block mb-1.5">Frequentie</label>
              <div className="space-y-1.5">
                {FREQUENCIES.map(({ value, label, desc }) => (
                  <label
                    key={value}
                    className={`flex items-center gap-3 p-2.5 rounded-lg border cursor-pointer transition-all ${
                      frequency === value
                        ? "border-orange-300 bg-orange-50/50 ring-1 ring-orange-100"
                        : "border-gray-200 hover:border-gray-300"
                    }`}
                  >
                    <input
                      type="radio"
                      name="frequency"
                      value={value}
                      checked={frequency === value}
                      onChange={(e) => setFrequency(e.target.value)}
                      className="sr-only"
                    />
                    <span className={`w-4 h-4 rounded-full border-[1.5px] flex items-center justify-center shrink-0 ${
                      frequency === value ? "border-orange-500 bg-orange-500" : "border-gray-300"
                    }`}>
                      {frequency === value && (
                        <span className="w-1.5 h-1.5 rounded-full bg-white" />
                      )}
                    </span>
                    <div>
                      <p className="text-sm font-medium text-gray-900">{label}</p>
                      <p className="text-xs text-gray-500">{desc}</p>
                    </div>
                  </label>
                ))}
              </div>
            </div>

            {/* Error */}
            {error && (
              <p className="text-sm text-red-600 bg-red-50 border border-red-100 rounded-lg p-2.5">{error}</p>
            )}

            {/* Submit */}
            <button
              type="submit"
              disabled={loading || !email.trim()}
              className="w-full py-2.5 bg-orange-500 text-white rounded-lg text-sm font-semibold hover:bg-orange-600 disabled:opacity-50 transition-colors flex items-center justify-center gap-2"
            >
              {loading ? (
                <>
                  <span className="w-4 h-4 border-2 border-white/30 border-t-white rounded-full animate-spin" />
                  Aanmaken...
                </>
              ) : (
                <>
                  <Bell01 className="w-4 h-4" />
                  Attendering instellen
                </>
              )}
            </button>

            <p className="text-[10px] text-gray-400 text-center">
              U ontvangt een bevestigingsmail. Uw gegevens worden niet gedeeld met derden.
            </p>
          </form>
        )}
      </div>
    </div>
  );
}
