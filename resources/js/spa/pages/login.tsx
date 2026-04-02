import { useState } from "react";
import { Link, useNavigate } from "react-router";
import { Mail01, Lock01, LogIn01 } from "@untitledui/icons";
import { useAuth } from "../providers/auth-provider";

export function LoginPage() {
  const { login, user } = useAuth();
  const navigate = useNavigate();
  const [email, setEmail] = useState("");
  const [password, setPassword] = useState("");
  const [remember, setRemember] = useState(false);
  const [error, setError] = useState("");
  const [loading, setLoading] = useState(false);

  if (user) { navigate("/mijn-account", { replace: true }); return null; }

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    setError("");
    setLoading(true);
    try {
      await login(email, password, remember);
      navigate("/mijn-account");
    } catch (err: any) {
      setError(err.message || "Inloggen mislukt.");
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className="min-h-[calc(100vh-3.5rem)] flex items-center justify-center px-4 py-12 bg-gray-50">
      <div className="w-full max-w-sm">
        {/* Header */}
        <div className="text-center mb-8">
          <div className="w-12 h-12 rounded-xl bg-brand-700 flex items-center justify-center mx-auto mb-4">
            <span className="text-white font-bold text-sm">oP</span>
          </div>
          <h1 className="text-xl font-bold text-gray-900">Inloggen</h1>
          <p className="text-sm text-gray-500 mt-1">Beheer uw attenderingen en documenten</p>
        </div>

        <form onSubmit={handleSubmit} className="bg-white rounded-xl border border-gray-200 p-6 shadow-sm space-y-4">
          {error && (
            <p className="text-sm text-red-600 bg-red-50 border border-red-100 rounded-lg p-2.5">{error}</p>
          )}

          <div>
            <label className="text-sm font-medium text-gray-700 block mb-1.5">E-mailadres</label>
            <div className="flex items-center gap-2 border border-gray-200 rounded-lg px-3 py-2.5 focus-within:border-blue-300 focus-within:ring-2 focus-within:ring-blue-50">
              <Mail01 className="w-4 h-4 text-gray-400 shrink-0" />
              <input type="email" value={email} onChange={(e) => setEmail(e.target.value)} required
                placeholder="uw@email.nl" className="flex-1 bg-transparent border-0 outline-none text-sm text-gray-900 placeholder:text-gray-400" />
            </div>
          </div>

          <div>
            <label className="text-sm font-medium text-gray-700 block mb-1.5">Wachtwoord</label>
            <div className="flex items-center gap-2 border border-gray-200 rounded-lg px-3 py-2.5 focus-within:border-blue-300 focus-within:ring-2 focus-within:ring-blue-50">
              <Lock01 className="w-4 h-4 text-gray-400 shrink-0" />
              <input type="password" value={password} onChange={(e) => setPassword(e.target.value)} required
                placeholder="Wachtwoord" className="flex-1 bg-transparent border-0 outline-none text-sm text-gray-900 placeholder:text-gray-400" />
            </div>
          </div>

          <div className="flex items-center justify-between">
            <label className="flex items-center gap-2 cursor-pointer">
              <input type="checkbox" checked={remember} onChange={(e) => setRemember(e.target.checked)}
                className="w-4 h-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500" />
              <span className="text-sm text-gray-600">Onthoud mij</span>
            </label>
          </div>

          <button type="submit" disabled={loading}
            className="w-full py-2.5 bg-brand-700 text-white rounded-lg text-sm font-semibold hover:bg-brand-800 disabled:opacity-50 transition-colors flex items-center justify-center gap-2">
            {loading ? (
              <span className="w-4 h-4 border-2 border-white/30 border-t-white rounded-full animate-spin" />
            ) : (
              <LogIn01 className="w-4 h-4" />
            )}
            Inloggen
          </button>
        </form>

        <p className="text-center text-sm text-gray-500 mt-4">
          Nog geen account?{" "}
          <Link to="/registreren" className="text-blue-600 font-medium hover:text-blue-700">
            Registreren
          </Link>
        </p>
      </div>
    </div>
  );
}
