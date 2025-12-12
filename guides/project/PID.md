Hier is een **volledige, professioneel uitgewerkte Project Initiatie (Project Initiatief-document)** volgens de methode van **Roel Grit**, volledig afgestemd op jouw project **OpenPublicaties / Open Source Woo-Voorziening**.

Je kunt dit document 1-op-1 opnemen in je projectdossier (PID/PID-light).

---

# **PROJECTINITIATIEF (Roel Grit Stijl)**

## **Projectnaam**

**OpenPublicaties – Open Source Woo Voorziening (OS-WooV)**

---

## **Projectdoelstelling**

Het doel van het project *OpenPublicaties* is het realiseren van een volledig open source, lichtgewicht en state-of-the-art Woo-voorziening die actief openbaar maken eenvoudig, betrouwbaar en duurzaam ondersteunt. De voorziening fungeert als een **blauwdruk** voor bestuursorganen en als **innovatieve referentie-implementatie** voor het Ministerie van BZK, waarmee wordt aangetoond dat moderne technieken het Woo-proces aanzienlijk kunnen versnellen en vereenvoudigen.

Het project levert een werkende, schaalbare, modulair uitbreidbare voorziening waarmee documenten automatisch worden geharvest, gemetadateerd, geïndexeerd en actief openbaar gemaakt, in nauwe aansluiting op de Woo-index en open.overheid.nl.

---

## **Aanleiding**

De huidige keten voor actieve openbaarmaking is complex, technisch zwaar en historisch gegroeid. In de praktijk ervaren bestuursorganen:

* lange doorlooptijden bij implementatie;
* afhankelijkheid van leveranciers met gesloten technologie;
* technische knelpunten rondom schaalbaarheid, indexering en toegankelijkheid;
* beperkte flexibiliteit om eigen innovaties toe te passen;
* hoge kosten voor ontwikkeling, beheer en doorontwikkeling.

Daarom is *OpenPublicaties* ontwikkeld als **disruptief alternatief** én **praktisch bewijs** dat het anders kan: sneller, lichter, betrouwbaarder en volledig open.

De aanleiding is tweeledig:

1. **Technisch:** demonstreren dat moderne open source technologie (Laravel 12, Go, Typesense, AI, PostgreSQL) significant efficiënter werkt dan de legacy stack.
2. **Bestuurlijk:** bestuursorganen concrete handelingsopties bieden voor het realiseren van Woo-verplichtingen zonder afhankelijkheid van zware centrale voorzieningen.

---

## **Projectresultaat**

Aan het einde van het project is opgeleverd:

### **1. Een volledige open source Woo-voorziening**, bestaande uit:

* Webapplicatie (Laravel 12 + Tailwind 4)
* Actieve harvester written in Go (open.overheid.nl + ORI API)
* Realtime indexering via Typesense
* PostgreSQL 12 datastore
* Local AI (Ollama) voor automatische metadata, duiding en classificatie
* Multi-tenant structuur voor meerdere bestuursorganen
* Dashboard voor Woo-behandelaren

### **2. Compatibiliteit met Woo-stelsel**

* Ondersteuning Woo-categorieën
* MDTO-structuren
* Zaakcontext in lijn met Woo-proces
* Export- en publiceerfunctionaliteit

### **3. Documentatie & Blauwdruk**

* Volledige technische documentatie
* Referentiearchitectuur
* API-documentatie
* Installatiehandleiding
* Governance-voorstel voor gezamenlijke doorontwikkeling

### **4. Stakeholdervoorstel (voor BZK)**

OpenPublicaties als pilot en referentie-implementatie positioneren naast de landelijke voorzieningen, als versneller voor innovatie en verbeterde naleving van de Woo.

---

## **Afbakening**

**Binnen scope**

* Technische realisatie MVP en baseline-functionaliteit
* Koppelingen met open.overheid.nl + ORI
* Metadata- en documentverwerking conform MDTO
* Actieve openbaarmaking met basiscontrole
* Dashboard voor beheerders en redacteuren
* Publicatie op opub.nl

**Buiten scope**

* Vervanging van open.overheid.nl
* Juridische beoordeling van alle Woo-documenten
* Landelijke governance- en financieringsstructuur (wel voorstel hiervoor)
* Integratie met zaaksystemen buiten MVP-context

---

## **Business Case / Nut & Noodzaak**

OpenPublicaties draagt direct bij aan:

### ✔ **Snellere Woo-compliance**

Bestuursorganen kunnen direct publiceren zonder zware implementatieprojecten.

### ✔ **Lagere ICT-kosten**

Geen licenties, geen vendor lock-in, minimaal onderhoud.

### ✔ **Transparantie en innovatie**

Open source, controleerbaar, uitbreidbaar, auditbaar.

### ✔ **Betere informatiehuishouding**

Automatische indexering, AI-ondersteuning en logische metadatastromen.

### ✔ **Aanpasbaarheid aan toekomstige regelgeving**

MDTO, TOOI en zaakcontext zijn modulair en uitbreidbaar.

De investering bestaat vooral uit ontwikkeltijd en minimale hostingkosten. De baten zijn structureel.

---

## **Projectorganisatie**

### **Opdrachtgever**

**Said Ahamri – CodeLabs B.V. / Successio B.V.**

### **Projectleider**

**Said Ahamri**
(Hands-on projectmanager, solution architect)

### **Ontwikkelteam**

* **Lead Developer:** CodeLabs Development
* **Go Engineers:** CodeLabs
* **AI Integratie:** CodeLabs AI team
* **UI/UX:** CodeLabs Design

### **Stakeholders**

* Ministerie van BZK
* Bestuursorganen (gemeenten, provincies, uitvoeringsorganisaties)
* Archiefdiensten
* Woo-behandelaren en informatieadviseurs

---

## **Randvoorwaarden**

* Gebruik uitsluitend open source tooling
* Technische stack moet licht, schaalbaar en future-proof blijven
* Code wordt gepubliceerd via publieke Git repository
* Ondersteuning MDTO 2.x en Woo-categorieën is verplicht
* Privacy-by-design & security-by-design

---

## **Risico’s**

| Risico                         | Impact | Maatregel                                                |
| ------------------------------ | ------ | -------------------------------------------------------- |
| Onvoldoende alignment met BZK  | Hoog   | Tijdige gedeelde sessies + blauwdruk als voorstel        |
| Wijzigende Woo-inrichting      | Medium | Modulariteit + configuratie i.p.v. hardcode              |
| Misverstanden met leveranciers | Medium | Transparante communicatie: OS-project, geen concurrentie |
| Resourceschaarste              | Laag   | Agile planning + prioritering                            |


## **Projectacceptatie**

Het project is succesvol wanneer:

1. De open source Woo-voorziening operationeel is.
2. Bestuursorganen documenten kunnen publiceren conform Woo-eisen.
3. Harvesting + indexing volledig automatisch functioneren.
4. BZK de blauwdruk accepteert als gesprekspartner en mogelijke pilotbasis.
5. Documentatie compleet is en herbruikbaar voor andere organisaties.

---

## **Bijdrage aan de Wet open overheid (Woo)**

OpenPublicaties versterkt de doelen van de Woo door:

* actieve openbaarmaking te vereenvoudigen;
* toegankelijkheid en vindbaarheid te verbeteren;
* de transparantie van digitale overheidsinformatie te vergroten;
* bestuursorganen meer grip te geven op hun informatiehuishouding.

---

## **Verzoek aan de opdrachtgever / stakeholders**

* Goedkeuring van het projectinitiatief
* Benoeming van betrokken stakeholders voor samenwerkings- en afstemsessies
* Toestemming om de blauwdruk formeel aan te bieden aan BZK

---

# **Einde Projectinitiatief-document (Roel Grit stijl)**

Wil je dat ik dit omzet naar een **opgemaakte PDF**, **Word-document**, **officiële PID-versie**, of wil je er een **UX-diagram / blokmodel** van?
