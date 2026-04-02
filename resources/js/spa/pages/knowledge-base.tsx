import { Link } from "react-router";
import { BookOpen01, Code01, Calendar, ChevronRight, LinkExternal01 } from "@untitledui/icons";

const SECTIONS = [
  {
    id: "artikelen",
    icon: BookOpen01,
    title: "Artikelen",
    desc: "Achtergrondartikelen over open overheid, WOO, en transparantie",
    items: [
      { title: "Wat is de Wet Open Overheid (WOO)?", desc: "Alles over de WOO en hoe je documenten kunt opvragen", url: "/blog" },
      { title: "Hoe werkt het Open Overheid platform?", desc: "Een uitleg over onze zoektechnologie en AI-verrijking", url: "/blog" },
      { title: "Dossiers begrijpen", desc: "Hoe overheidsdocumenten in dossiers worden gegroepeerd", url: "/blog" },
    ],
  },
  {
    id: "ontwikkelaars",
    icon: Code01,
    title: "Ontwikkelaars",
    desc: "API documentatie en integraties voor developers",
    items: [
      { title: "API Documentatie (Swagger)", desc: "Interactieve API docs met voorbeelden", url: "/api/docs" },
      { title: "Zoek API", desc: "Full-text search met filters en facets", url: "/api/docs#/Zoeken" },
      { title: "Ingest API", desc: "Data aanleveren — push documenten naar OPub", url: "/api/docs#/Ingest" },
      { title: "Chat API", desc: "AI-powered vraag & antwoord over overheidsdocumenten", url: "/api/docs#/Chat" },
    ],
  },
  {
    id: "evenementen",
    icon: Calendar,
    title: "Evenementen",
    desc: "Aankomende en afgelopen evenementen",
    items: [
      { title: "Open Data Dag 2026", desc: "Jaarlijkse conferentie over open overheid en transparantie", url: "#" },
      { title: "API Workshop", desc: "Leer werken met de OPub API", url: "#" },
    ],
  },
];

export function KnowledgeBasePage() {
  return (
    <div className="mx-auto max-w-[1187px] px-4 py-10">
      <div className="mb-10">
        <h1 className="text-2xl font-bold text-text-primary">Kennisbank</h1>
        <p className="text-sm text-text-tertiary mt-1">Artikelen, documentatie, en evenementen</p>
      </div>

      <div className="space-y-10">
        {SECTIONS.map(({ id, icon: Icon, title, desc, items }) => (
          <section key={id} id={id}>
            <div className="flex items-center gap-3 mb-4">
              <div className="w-9 h-9 rounded-lg bg-bg-brand-secondary flex items-center justify-center">
                <Icon className="w-4 h-4 text-fg-brand-primary" />
              </div>
              <div>
                <h2 className="text-base font-semibold text-text-primary">{title}</h2>
                <p className="text-xs text-text-tertiary">{desc}</p>
              </div>
            </div>

            <div className="grid gap-2">
              {items.map((item) => {
                const isExternal = item.url.startsWith("http") || item.url.startsWith("/api");
                const Comp = isExternal ? "a" : Link;
                const props = isExternal
                  ? { href: item.url, target: "_blank", rel: "noopener" }
                  : { to: item.url };

                return (
                  <Comp
                    key={item.title}
                    {...(props as any)}
                    className="flex items-center gap-4 p-4 rounded-xl border border-border-secondary hover:border-border-brand hover:shadow-xs transition-all group bg-bg-primary"
                  >
                    <div className="flex-1 min-w-0">
                      <h3 className="text-sm font-medium text-text-primary group-hover:text-text-brand-secondary transition-colors">
                        {item.title}
                      </h3>
                      <p className="text-xs text-text-tertiary mt-0.5">{item.desc}</p>
                    </div>
                    {isExternal ? (
                      <LinkExternal01 className="w-4 h-4 text-fg-quaternary group-hover:text-fg-brand-primary shrink-0" />
                    ) : (
                      <ChevronRight className="w-4 h-4 text-fg-quaternary group-hover:text-fg-brand-primary shrink-0" />
                    )}
                  </Comp>
                );
              })}
            </div>
          </section>
        ))}
      </div>
    </div>
  );
}
