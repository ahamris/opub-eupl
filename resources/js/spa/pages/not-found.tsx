import { Link } from "react-router";

export function NotFoundPage() {
  return (
    <div className="flex flex-col items-center justify-center py-24 px-4">
      <p className="text-6xl font-bold text-text-quaternary">404</p>
      <p className="text-text-tertiary mt-2">Pagina niet gevonden</p>
      <Link
        to="/"
        className="mt-4 px-4 py-2 bg-bg-brand-solid text-text-white rounded-lg text-sm font-semibold hover:bg-bg-brand-solid_hover transition-colors"
      >
        Naar homepage
      </Link>
    </div>
  );
}
