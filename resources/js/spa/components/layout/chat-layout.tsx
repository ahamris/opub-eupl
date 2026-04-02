import { Outlet, Link, useLocation } from "react-router";
import { SearchLg, MessageChatCircle, Folder, Home01, Menu01, XClose, BarChart01, BookOpen01, Mail01 } from "@untitledui/icons";
import { useState } from "react";

const NAV = [
  { to: "/", label: "Home", icon: Home01 },
  { to: "/zoeken", label: "Zoeken", icon: SearchLg },
  { to: "/collecties", label: "Thema's & dossiers", icon: Folder },
  { to: "/dashboard", label: "Dashboard", icon: BarChart01 },
  { to: "/kennisbank", label: "Kennisbank", icon: BookOpen01 },
  { to: "/chat", label: "AI-chat", icon: MessageChatCircle },
];

export function ChatLayout() {
  const { pathname } = useLocation();
  const [mobileOpen, setMobileOpen] = useState(false);

  return (
    <div className="h-screen flex flex-col bg-white">
      {/* Zelfde header als AppLayout */}
      <header className="sticky top-0 z-40 border-b border-gray-100 bg-white/80 backdrop-blur-lg shrink-0">
        <div className="mx-auto max-w-[1187px] flex items-center justify-between px-4 sm:px-6 h-14">
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

      {/* Chat content neemt rest van het scherm */}
      <Outlet />
    </div>
  );
}
