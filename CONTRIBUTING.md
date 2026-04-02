# Bijdragen aan OpenPublicaties

Welkom, en bedankt voor uw interesse in OpenPublicaties! Dit document beschrijft hoe u kunt bijdragen aan het project — of het nu gaat om code, documentatie, onderzoek of nieuwe ideeën.

OpenPublicaties richt zich op het ontsluiten van **alle Nederlandse overheidspublicaties**: wet- en regelgeving, beleidsstukken, rapporten, bekendmakingen, onderzoeken, niet-toepassingen en meer. Het platform bevindt zich in een vroege fase en verwelkomt bijdragen, verkenningen en onderzoek vanuit de gehele gemeenschap.

---

## Gedragscode

Wij verwachten dat alle bijdragers:

- **Respectvol** met elkaar omgaan, ongeacht achtergrond of expertise.
- Handelen vanuit **publieke sector waarden**: transparantie, betrouwbaarheid en dienstbaarheid.
- Bijdragen aan een **open en constructieve samenwerking** waarin iedereen zich welkom voelt.
- Discriminatie, intimidatie en onprofessioneel gedrag worden niet getolereerd.

Dit project staat in dienst van de samenleving. Wij vragen bijdragers om die verantwoordelijkheid mee te dragen.

## Een bug melden

1. Controleer of de bug al gemeld is in [GitHub Issues](https://github.com/ahamris/opub-eupl/issues).
2. Maak een nieuw issue aan met:
   - Een duidelijke, beknopte titel.
   - Stappen om het probleem te reproduceren.
   - Verwacht gedrag versus werkelijk gedrag.
   - Screenshots of logbestanden indien relevant.
   - Uw omgeving (PHP-versie, browser, besturingssysteem).

## Een feature of onderzoeksrichting aandragen

OpenPublicaties is breed opgezet — we staan open voor nieuwe functionaliteiten, koppelingen met overheidsbronnen, en onderzoeksrichtingen.

1. **Open eerst een issue** op GitHub waarin u uw voorstel beschrijft.
2. Beschrijf het probleem dat u wilt oplossen of de richting die u wilt verkennen.
3. Wacht op feedback van de beheerders voordat u een Pull Request opent.
4. Discussie is waardevol: ook als een idee niet direct leidt tot code, kan het richting geven aan het project.

## Forken

**Ongereviewde forks worden ontmoedigd.** OpenPublicaties is een gemeenschapsproject en fragmentatie ondermijnt de doelstelling.

- Wilt u het project forken of een afgeleide maken? **Open eerst een [GitHub Issue](https://github.com/ahamris/opub-eupl/issues)** om uw intentie te bespreken.
- In veel gevallen is een bijdrage aan het hoofdproject waardevoller dan een aparte fork.
- Samen kunnen we afstemmen hoe uw ideeën het beste tot hun recht komen.

## Pull request proces

### Branch naamgeving

Gebruik een van de volgende prefixen:

- `feature/` — Nieuwe functionaliteit (bijv. `feature/zoek-wet-regelgeving`)
- `fix/` — Bugfix (bijv. `fix/typesense-sync-timeout`)
- `docs/` — Documentatie (bijv. `docs/api-voorbeelden`)
- `refactor/` — Refactoring zonder functionele wijziging
- `research/` — Verkennend onderzoek of prototype

### Commit stijl

Gebruik de volgende conventie:

```
type(scope): korte beschrijving

- Detail 1
- Detail 2
```

Types: `feat`, `fix`, `docs`, `style`, `refactor`, `test`, `chore`, `research`

### Pull request vereisten

1. **Eén PR per onderwerp** — houd wijzigingen gefocust en reviewbaar.
2. **Beschrijf wat en waarom** — niet alleen wat er veranderd is, maar waarom.
3. **Tests** — voeg tests toe of werk bestaande tests bij waar nodig.
4. **Review** — elke PR wordt gereviewd door minimaal één beheerder van CodeLabs B.V.
5. **CI moet slagen** — zorg dat alle checks groen zijn voordat u review aanvraagt.

## Codestijl

- **PHP**: PSR-12, afgedwongen via Laravel Pint.
- **TypeScript/React**: ESLint + Prettier.
- **CSS**: Tailwind CSS utility classes.
- **Nederlandse comments** zijn toegestaan en zelfs aangemoedigd waar dit de leesbaarheid voor de doelgroep vergroot.
- Volg bestaande patronen in de codebase — consistentie gaat boven persoonlijke voorkeur.

## Commercieel gebruik

Commercieel gebruik van OpenPublicaties of afgeleiden daarvan is **niet toegestaan zonder schriftelijke toestemming** van CodeLabs B.V. Dit geldt voor:

- Het aanbieden van OpenPublicaties als betaalde dienst.
- Het inbedden van (delen van) het platform in commerciële producten.
- Het gebruik van de codebase voor commerciële doeleinden zonder licentieovereenkomst.

Neem bij twijfel contact op met CodeLabs B.V. via [code-labs.nl](https://code-labs.nl) of info@codelabs.nl.

## Licentie

Door bij te dragen aan OpenPublicaties gaat u akkoord dat uw bijdragen worden gelicentieerd onder de **EUPL-1.2**. Afgeleiden blijven open source onder dezelfde voorwaarden.

## Contact

Vragen over bijdragen, samenwerking of het project?

**CodeLabs B.V.** — Ontwikkelaar en beheerder van OpenPublicaties

- Website: [code-labs.nl](https://code-labs.nl)
- E-mail: info@codelabs.nl
- GitHub: [Issues](https://github.com/ahamris/opub-eupl/issues)

---

*Elke bijdrage — groot of klein — helpt mee aan een transparantere overheid. Welkom aan boord.*
