import { useState } from "react";
import { Link, useNavigate } from "react-router";
import { Mail01, Lock01, User01, UserPlus01 } from "@untitledui/icons";
import { useAuth } from "../providers/auth-provider";

export function RegisterPage() {
  const { register, user } = useAuth();
  const navigate = useNavigate();
  const [form, setForm] = useState({ name: "", last_name: "", email: "", password: "", password_confirmation: "" });
  const [error, setError] = useState("");
  const [loading, setLoading] = useState(false);

  if (user) { navigate("/mijn-account", { replace: true }); return null; }

  const set = (key: string) => (e: React.ChangeEvent<HTMLInputElement>) =>
    setForm((f) => ({ ...f, [key]: e.target.value }));

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    setError("");
    setLoading(true);
    try {
      await register(form);
      navigate("/mijn-account");
    } catch (err: any) {
      setError(err.message || "Registratie mislukt.");
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className="min-h-[calc(100vh-3.5rem)] flex items-center justify-center px-4 py-12 bg-gray-50">
      <div className="w-full max-w-sm">
        <div className="text-center mb-8">
          <div className="w-12 h-12 rounded-xl bg-brand-700 flex items-center justify-center mx-auto mb-4">
            <span className="text-white font-bold text-sm">oP</span>
          </div>
          <h1 className="text-xl font-bold text-gray-900">Account aanmaken</h1>
          <p className="text-sm text-gray-500 mt-1">Gratis account voor attenderingen en meer</p>
        </div>

        <form onSubmit={handleSubmit} className="bg-white rounded-xl border border-gray-200 p-6 shadow-sm space-y-4">
          {error && (
            <p className="text-sm text-red-600 bg-red-50 border border-red-100 rounded-lg p-2.5">{error}</p>
          )}

          <div className="grid grid-cols-2 gap-3">
            <div>
              <label className="text-sm font-medium text-gray-700 block mb-1.5">Voornaam</label>
              <div className="flex items-center gap-2 border border-gray-200 rounded-lg px-3 py-2.5 focus-within:border-blue-300 focus-within:ring-2 focus-within:ring-blue-50">
                <User01 className="w-4 h-4 text-gray-400 shrink-0" />
                <input type="text" value={form.name} onChange={set("name")} required
                  className="flex-1 bg-transparent border-0 outline-none text-sm text-gray-900 placeholder:text-gray-400 min-w-0" />
              </div>
            </div>
            <div>
              <label className="text-sm font-medium text-gray-700 block mb-1.5">Achternaam</label>
              <div className="flex items-center gap-2 border border-gray-200 rounded-lg px-3 py-2.5 focus-within:border-blue-300 focus-within:ring-2 focus-within:ring-blue-50">
                <input type="text" value={form.last_name} onChange={set("last_name")} required
                  className="flex-1 bg-transparent border-0 outline-none text-sm text-gray-900 placeholder:text-gray-400 min-w-0" />
              </div>
            </div>
          </div>

          <div>
            <label className="text-sm font-medium text-gray-700 block mb-1.5">E-mailadres</label>
            <div className="flex items-center gap-2 border border-gray-200 rounded-lg px-3 py-2.5 focus-within:border-blue-300 focus-within:ring-2 focus-within:ring-blue-50">
              <Mail01 className="w-4 h-4 text-gray-400 shrink-0" />
              <input type="email" value={form.email} onChange={set("email")} required
                placeholder="uw@email.nl" className="flex-1 bg-transparent border-0 outline-none text-sm text-gray-900 placeholder:text-gray-400" />
            </div>
          </div>

          <div>
            <label className="text-sm font-medium text-gray-700 block mb-1.5">Wachtwoord</label>
            <div className="flex items-center gap-2 border border-gray-200 rounded-lg px-3 py-2.5 focus-within:border-blue-300 focus-within:ring-2 focus-within:ring-blue-50">
              <Lock01 className="w-4 h-4 text-gray-400 shrink-0" />
              <input type="password" value={form.password} onChange={set("password")} required minLength={8}
                placeholder="Min. 8 tekens" className="flex-1 bg-transparent border-0 outline-none text-sm text-gray-900 placeholder:text-gray-400" />
            </div>
          </div>

          <div>
            <label className="text-sm font-medium text-gray-700 block mb-1.5">Wachtwoord bevestigen</label>
            <div className="flex items-center gap-2 border border-gray-200 rounded-lg px-3 py-2.5 focus-within:border-blue-300 focus-within:ring-2 focus-within:ring-blue-50">
              <Lock01 className="w-4 h-4 text-gray-400 shrink-0" />
              <input type="password" value={form.password_confirmation} onChange={set("password_confirmation")} required minLength={8}
                placeholder="Herhaal wachtwoord" className="flex-1 bg-transparent border-0 outline-none text-sm text-gray-900 placeholder:text-gray-400" />
            </div>
          </div>

          <button type="submit" disabled={loading}
            className="w-full py-2.5 bg-brand-700 text-white rounded-lg text-sm font-semibold hover:bg-brand-800 disabled:opacity-50 transition-colors flex items-center justify-center gap-2">
            {loading ? (
              <span className="w-4 h-4 border-2 border-white/30 border-t-white rounded-full animate-spin" />
            ) : (
              <UserPlus01 className="w-4 h-4" />
            )}
            Account aanmaken
          </button>
        </form>

        <p className="text-center text-sm text-gray-500 mt-4">
          Al een account?{" "}
          <Link to="/inloggen" className="text-blue-600 font-medium hover:text-blue-700">
            Inloggen
          </Link>
        </p>
      </div>
    </div>
  );
}
