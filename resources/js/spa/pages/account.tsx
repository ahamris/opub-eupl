import { useState, useEffect } from "react";
import { useNavigate, Link } from "react-router";
import {
  Bell01, User01, Settings01, Trash01, SearchLg, Check, XClose,
  Mail01, Lock01, ChevronRight, Clock, Toggle01Right, Toggle01Left,
} from "@untitledui/icons";
import { useAuth } from "../providers/auth-provider";

interface Subscription {
  id: number;
  search_query: string | null;
  filters: Record<string, any> | null;
  formatted_filters: string;
  frequency: string;
  frequency_label: string;
  is_active: boolean;
  is_verified: boolean;
  created_at: string;
  last_sent_at: string | null;
}

function getCsrf(): string {
  return document.querySelector<HTMLMetaElement>('meta[name="csrf-token"]')?.content || "";
}

async function authFetch<T>(path: string, options?: RequestInit): Promise<T> {
  const res = await fetch(path, {
    headers: { Accept: "application/json", "Content-Type": "application/json", "X-CSRF-TOKEN": getCsrf() },
    credentials: "same-origin",
    ...options,
  });
  if (!res.ok) {
    const data = await res.json().catch(() => ({}));
    throw new Error(data.message || `Error ${res.status}`);
  }
  return res.json();
}

type Tab = "subscriptions" | "profile" | "password";

export function AccountPage() {
  const { user, loading: authLoading, logout, refresh } = useAuth();
  const navigate = useNavigate();
  const [tab, setTab] = useState<Tab>("subscriptions");

  if (!authLoading && !user) { navigate("/inloggen", { replace: true }); return null; }
  if (authLoading) return <div className="flex items-center justify-center py-20"><div className="w-6 h-6 border-2 border-blue-400 border-t-transparent rounded-full animate-spin" /></div>;

  const tabs: { key: Tab; label: string; icon: typeof Bell01 }[] = [
    { key: "subscriptions", label: "Attenderingen", icon: Bell01 },
    { key: "profile", label: "Profiel", icon: User01 },
    { key: "password", label: "Wachtwoord", icon: Lock01 },
  ];

  return (
    <div className="mx-auto max-w-[1187px] px-4 sm:px-6 py-8">
      {/* Header */}
      <div className="flex items-center justify-between mb-6">
        <div>
          <h1 className="text-xl font-bold text-gray-900">Mijn account</h1>
          <p className="text-sm text-gray-500 mt-0.5">
            {user!.name} {user!.last_name} · {user!.email}
          </p>
        </div>
        <button onClick={() => { logout(); navigate("/"); }}
          className="px-3 py-1.5 text-sm text-gray-500 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors">
          Uitloggen
        </button>
      </div>

      {/* Tabs */}
      <div className="flex gap-1 border-b border-gray-200 mb-6">
        {tabs.map(({ key, label, icon: Icon }) => (
          <button key={key} onClick={() => setTab(key)}
            className={`flex items-center gap-2 px-4 py-2.5 text-sm font-medium border-b-2 transition-colors -mb-px ${
              tab === key
                ? "border-blue-600 text-blue-700"
                : "border-transparent text-gray-500 hover:text-gray-700"
            }`}>
            <Icon className="w-4 h-4" /> {label}
          </button>
        ))}
      </div>

      {/* Tab content */}
      {tab === "subscriptions" && <SubscriptionsTab />}
      {tab === "profile" && <ProfileTab user={user!} onUpdate={refresh} />}
      {tab === "password" && <PasswordTab />}
    </div>
  );
}

/* ─── Subscriptions Tab ──────────────────────────────────────── */

function SubscriptionsTab() {
  const [subs, setSubs] = useState<Subscription[]>([]);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    authFetch<Subscription[]>("/auth/subscriptions")
      .then(setSubs)
      .catch(() => {})
      .finally(() => setLoading(false));
  }, []);

  const toggle = async (id: number) => {
    const data = await authFetch<{ is_active: boolean }>(`/auth/subscriptions/${id}/toggle`, { method: "PATCH" });
    setSubs((s) => s.map((x) => (x.id === id ? { ...x, is_active: data.is_active } : x)));
  };

  const remove = async (id: number) => {
    if (!confirm("Weet u zeker dat u deze attendering wilt verwijderen?")) return;
    await authFetch(`/auth/subscriptions/${id}`, { method: "DELETE" });
    setSubs((s) => s.filter((x) => x.id !== id));
  };

  if (loading) return <div className="py-12 text-center text-sm text-gray-400">Laden...</div>;

  if (subs.length === 0) {
    return (
      <div className="text-center py-16">
        <div className="w-14 h-14 rounded-2xl bg-gray-100 flex items-center justify-center mx-auto mb-4">
          <Bell01 className="w-6 h-6 text-gray-400" />
        </div>
        <p className="text-gray-700 font-semibold">Geen attenderingen</p>
        <p className="text-sm text-gray-400 mt-1">
          Zoek documenten en klik op "Attendering" om meldingen in te stellen.
        </p>
        <Link to="/zoeken" className="inline-flex items-center gap-1.5 mt-4 px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700 transition-colors">
          <SearchLg className="w-4 h-4" /> Naar zoeken
        </Link>
      </div>
    );
  }

  return (
    <div className="space-y-3">
      {subs.map((s) => (
        <div key={s.id} className="flex items-center gap-4 p-4 rounded-xl border border-gray-200 bg-white">
          <div className="flex-1 min-w-0">
            <div className="flex items-center gap-2">
              <p className="text-sm font-semibold text-gray-900 truncate">
                {s.search_query || "Alle documenten"}
              </p>
              {!s.is_verified && (
                <span className="shrink-0 px-2 py-0.5 rounded-full bg-amber-50 text-amber-700 text-[10px] font-semibold">
                  Niet bevestigd
                </span>
              )}
              {s.is_verified && s.is_active && (
                <span className="shrink-0 px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-700 text-[10px] font-semibold">
                  Actief
                </span>
              )}
              {s.is_verified && !s.is_active && (
                <span className="shrink-0 px-2 py-0.5 rounded-full bg-gray-100 text-gray-500 text-[10px] font-semibold">
                  Gepauzeerd
                </span>
              )}
            </div>
            <div className="flex items-center gap-3 mt-1 text-xs text-gray-500">
              <span>{s.frequency_label}</span>
              {s.formatted_filters !== "Geen filters" && (
                <>
                  <span className="text-gray-300">·</span>
                  <span className="truncate">{s.formatted_filters}</span>
                </>
              )}
            </div>
            {s.last_sent_at && (
              <p className="text-[11px] text-gray-400 mt-1 flex items-center gap-1">
                <Clock className="w-3 h-3" />
                Laatst verstuurd: {new Date(s.last_sent_at).toLocaleDateString("nl-NL", { day: "numeric", month: "short", year: "numeric" })}
              </p>
            )}
          </div>
          <div className="flex items-center gap-1 shrink-0">
            {s.is_verified && (
              <button onClick={() => toggle(s.id)}
                className={`p-2 rounded-lg transition-colors ${s.is_active ? "text-emerald-600 hover:bg-emerald-50" : "text-gray-400 hover:bg-gray-50"}`}
                title={s.is_active ? "Pauzeren" : "Activeren"}>
                {s.is_active ? <Toggle01Right className="w-5 h-5" /> : <Toggle01Left className="w-5 h-5" />}
              </button>
            )}
            <button onClick={() => remove(s.id)}
              className="p-2 rounded-lg text-gray-400 hover:text-red-500 hover:bg-red-50 transition-colors" title="Verwijderen">
              <Trash01 className="w-4 h-4" />
            </button>
          </div>
        </div>
      ))}
    </div>
  );
}

/* ─── Profile Tab ────────────────────────────────────────────── */

function ProfileTab({ user, onUpdate }: { user: { name: string; last_name: string; email: string }; onUpdate: () => void }) {
  const [form, setForm] = useState({ name: user.name, last_name: user.last_name, email: user.email });
  const [loading, setLoading] = useState(false);
  const [success, setSuccess] = useState(false);
  const [error, setError] = useState("");

  const set = (key: string) => (e: React.ChangeEvent<HTMLInputElement>) => setForm((f) => ({ ...f, [key]: e.target.value }));

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    setLoading(true); setError(""); setSuccess(false);
    try {
      await authFetch("/auth/profile", { method: "PUT", body: JSON.stringify(form) });
      setSuccess(true);
      onUpdate();
    } catch (err: any) { setError(err.message); }
    finally { setLoading(false); }
  };

  return (
    <form onSubmit={handleSubmit} className="max-w-md space-y-4">
      {success && <p className="text-sm text-emerald-600 bg-emerald-50 border border-emerald-100 rounded-lg p-2.5 flex items-center gap-2"><Check className="w-4 h-4" /> Profiel bijgewerkt.</p>}
      {error && <p className="text-sm text-red-600 bg-red-50 border border-red-100 rounded-lg p-2.5">{error}</p>}

      <div className="grid grid-cols-2 gap-3">
        <div>
          <label className="text-sm font-medium text-gray-700 block mb-1.5">Voornaam</label>
          <input type="text" value={form.name} onChange={set("name")} required
            className="w-full px-3 py-2.5 rounded-lg border border-gray-200 text-sm text-gray-900 focus:border-blue-300 focus:ring-2 focus:ring-blue-50 outline-none" />
        </div>
        <div>
          <label className="text-sm font-medium text-gray-700 block mb-1.5">Achternaam</label>
          <input type="text" value={form.last_name} onChange={set("last_name")} required
            className="w-full px-3 py-2.5 rounded-lg border border-gray-200 text-sm text-gray-900 focus:border-blue-300 focus:ring-2 focus:ring-blue-50 outline-none" />
        </div>
      </div>

      <div>
        <label className="text-sm font-medium text-gray-700 block mb-1.5">E-mailadres</label>
        <input type="email" value={form.email} onChange={set("email")} required
          className="w-full px-3 py-2.5 rounded-lg border border-gray-200 text-sm text-gray-900 focus:border-blue-300 focus:ring-2 focus:ring-blue-50 outline-none" />
      </div>

      <button type="submit" disabled={loading}
        className="px-4 py-2.5 bg-brand-700 text-white rounded-lg text-sm font-semibold hover:bg-brand-800 disabled:opacity-50 transition-colors">
        {loading ? "Opslaan..." : "Profiel opslaan"}
      </button>
    </form>
  );
}

/* ─── Password Tab ───────────────────────────────────────────── */

function PasswordTab() {
  const [form, setForm] = useState({ current_password: "", password: "", password_confirmation: "" });
  const [loading, setLoading] = useState(false);
  const [success, setSuccess] = useState(false);
  const [error, setError] = useState("");

  const set = (key: string) => (e: React.ChangeEvent<HTMLInputElement>) => setForm((f) => ({ ...f, [key]: e.target.value }));

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    setLoading(true); setError(""); setSuccess(false);
    try {
      await authFetch("/auth/password", { method: "PUT", body: JSON.stringify(form) });
      setSuccess(true);
      setForm({ current_password: "", password: "", password_confirmation: "" });
    } catch (err: any) { setError(err.message); }
    finally { setLoading(false); }
  };

  return (
    <form onSubmit={handleSubmit} className="max-w-md space-y-4">
      {success && <p className="text-sm text-emerald-600 bg-emerald-50 border border-emerald-100 rounded-lg p-2.5 flex items-center gap-2"><Check className="w-4 h-4" /> Wachtwoord gewijzigd.</p>}
      {error && <p className="text-sm text-red-600 bg-red-50 border border-red-100 rounded-lg p-2.5">{error}</p>}

      <div>
        <label className="text-sm font-medium text-gray-700 block mb-1.5">Huidig wachtwoord</label>
        <input type="password" value={form.current_password} onChange={set("current_password")} required
          className="w-full px-3 py-2.5 rounded-lg border border-gray-200 text-sm text-gray-900 focus:border-blue-300 focus:ring-2 focus:ring-blue-50 outline-none" />
      </div>

      <div>
        <label className="text-sm font-medium text-gray-700 block mb-1.5">Nieuw wachtwoord</label>
        <input type="password" value={form.password} onChange={set("password")} required minLength={8}
          placeholder="Min. 8 tekens"
          className="w-full px-3 py-2.5 rounded-lg border border-gray-200 text-sm text-gray-900 focus:border-blue-300 focus:ring-2 focus:ring-blue-50 outline-none placeholder:text-gray-400" />
      </div>

      <div>
        <label className="text-sm font-medium text-gray-700 block mb-1.5">Bevestig nieuw wachtwoord</label>
        <input type="password" value={form.password_confirmation} onChange={set("password_confirmation")} required minLength={8}
          className="w-full px-3 py-2.5 rounded-lg border border-gray-200 text-sm text-gray-900 focus:border-blue-300 focus:ring-2 focus:ring-blue-50 outline-none" />
      </div>

      <button type="submit" disabled={loading}
        className="px-4 py-2.5 bg-brand-700 text-white rounded-lg text-sm font-semibold hover:bg-brand-800 disabled:opacity-50 transition-colors">
        {loading ? "Opslaan..." : "Wachtwoord wijzigen"}
      </button>
    </form>
  );
}
