import { Outlet, Link, useLocation } from "react-router";
import { SearchLg, MessageChatCircle, Folder, Home01, Menu01, XClose, BarChart01, BookOpen01, Mail01, Bell01 } from "@untitledui/icons";
import { useState } from "react";

const NAV = [
  { to: "/", label: "Home", icon: Home01 },
  { to: "/zoeken", label: "Zoeken", icon: SearchLg },
  { to: "/collecties", label: "Thema's & dossiers", icon: Folder },
  { to: "/dashboard", label: "Dashboard", icon: BarChart01 },
  { to: "/kennisbank", label: "Kennisbank", icon: BookOpen01 },
  { to: "/chat", label: "AI-chat", icon: MessageChatCircle },
];

export function AppLayout() {
  const { pathname } = useLocation();
  const [mobileOpen, setMobileOpen] = useState(false);

  return (
    <div className="min-h-screen bg-white flex flex-col">
      <header className="sticky top-0 z-40 border-b border-gray-100 bg-white/80 backdrop-blur-lg">
        <div className="mx-auto max-w-7xl flex items-center justify-between px-4 sm:px-6 h-14">
          <Link to="/" className="flex items-center gap-2">
            <div className="h-7 w-7 rounded-lg bg-blue-600 flex items-center justify-center">
              <span className="text-white font-bold text-[11px]">oP</span>
            </div>
            <span className="text-base font-semibold text-gray-900">oPub</span>
          </Link>

          <nav className="hidden md:flex items-center gap-0.5">
            {NAV.map(({ to, label, icon: Icon }) => {
              const active = to === "/" ? pathname === "/" : pathname.startsWith(to);
              return (
                <Link key={to} to={to}
                  className={`flex items-center gap-1.5 px-2.5 py-1.5 rounded-md text-[13px] font-medium transition-colors ${
                    active ? "bg-blue-50 text-blue-700" : "text-gray-500 hover:text-gray-900 hover:bg-gray-50"
                  }`}>
                  <Icon className="w-3.5 h-3.5" /> {label}
                </Link>
              );
            })}
            <div className="w-px h-5 bg-gray-200 mx-1" />
            <Link to="/contact" className="px-2.5 py-1.5 rounded-md text-[13px] font-medium text-gray-400 hover:text-gray-900 hover:bg-gray-50 transition-colors">Contact</Link>
          </nav>

          <button className="md:hidden p-2 text-gray-500" onClick={() => setMobileOpen(!mobileOpen)}>
            {mobileOpen ? <XClose className="w-5 h-5" /> : <Menu01 className="w-5 h-5" />}
          </button>
        </div>

        {mobileOpen && (
          <nav className="md:hidden border-t border-gray-100 px-3 py-2 bg-white">
            {[...NAV, { to: "/contact", label: "Contact", icon: Mail01 }].map(({ to, label, icon: Icon }) => (
              <Link key={to} to={to} onClick={() => setMobileOpen(false)}
                className="flex items-center gap-2.5 px-3 py-2.5 rounded-lg text-sm font-medium text-gray-600 hover:bg-gray-50">
                <Icon className="w-4 h-4" /> {label}
              </Link>
            ))}
          </nav>
        )}
      </header>

      <main className={pathname === "/chat" ? "flex-1 flex flex-col overflow-hidden" : "flex-1"}><Outlet /></main>

      {/* Footer — verberg op chat pagina */}
      {pathname !== "/chat" && (
      /* Footer — 3 kolommen: Functies | Over ons | Privacy */
      <footer className="border-t border-gray-100 bg-gray-50">
        <div className="mx-auto max-w-7xl px-4 sm:px-6 py-12">
          <div className="grid grid-cols-1 sm:grid-cols-3 gap-10 sm:gap-8">
            <div>
              <h4 className="text-xs font-semibold text-gray-900 uppercase tracking-wider mb-4">Functies</h4>
              <ul className="space-y-2.5">
                {[
                  { to: "/zoeken", l: "Federatief zoekportaal" },
                  { to: "/chat", l: "AI-chat (sovereign AI)" },
                  { to: "/collecties", l: "Thema's & dossiers" },
                  { to: "/dashboard", l: "Dashboard" },
                  { to: "/api/docs", l: "Open API (Swagger)" },
                ].map(({ to, l }) => (
                  <li key={to}><Link to={to} className="text-sm text-gray-500 hover:text-blue-600 transition-colors">{l}</Link></li>
                ))}
              </ul>
            </div>
            <div>
              <h4 className="text-xs font-semibold text-gray-900 uppercase tracking-wider mb-4">Over ons</h4>
              <ul className="space-y-2.5">
                {[
                  { to: "/over", l: "Over oPub" },
                  { to: "/kennisbank", l: "Kennisbank" },
                  { to: "/contact", l: "Contact & aansluiten" },
                  { to: "/kennisbank#ontwikkelaars", l: "Ontwikkelaars" },
                  { href: "https://github.com/code-labs-nl", l: "GitHub (EUPL 1.2)" },
                ].map((item) => (
                  <li key={item.l}>
                    {'href' in item
                      ? <a href={item.href} target="_blank" rel="noopener" className="text-sm text-gray-500 hover:text-blue-600 transition-colors">{item.l}</a>
                      : <Link to={item.to!} className="text-sm text-gray-500 hover:text-blue-600 transition-colors">{item.l}</Link>
                    }
                  </li>
                ))}
              </ul>
            </div>
            <div>
              <h4 className="text-xs font-semibold text-gray-900 uppercase tracking-wider mb-4">Privacy & juridisch</h4>
              <ul className="space-y-2.5">
                {[
                  { to: "/privacy", l: "Privacybeleid" },
                  { to: "/voorwaarden", l: "Gebruiksvoorwaarden" },
                  { to: "/cookies", l: "Cookiebeleid" },
                  { to: "/toegankelijkheid", l: "Toegankelijkheidsverklaring" },
                ].map(({ to, l }) => (
                  <li key={to}><Link to={to} className="text-sm text-gray-500 hover:text-blue-600 transition-colors">{l}</Link></li>
                ))}
              </ul>
            </div>
          </div>

          <div className="border-t border-gray-200 mt-10 pt-6 flex flex-col sm:flex-row items-center justify-between gap-3">
            <div className="flex items-center gap-2">
              <div className="h-6 w-6 rounded-md bg-blue-600 flex items-center justify-center">
                <span className="text-white font-bold text-[9px]">oP</span>
              </div>
              <span className="text-sm font-medium text-gray-600">oPub — OpenPublicaties</span>
            </div>
            <p className="text-xs text-gray-400">Gemaakt door CodeLabs B.V. · Open source onder EUPL 1.2 · Sovereign AI via Ollama + Geitje</p>
          </div>
        </div>
      </footer>
      )}
    </div>
  );
}
