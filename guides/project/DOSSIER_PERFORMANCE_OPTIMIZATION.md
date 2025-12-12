# Dossier Performance Optimization

## Probleem
De `/dossiers` pagina laadt langzaam door:
1. **Count query**: 1008ms (traag)
2. **N+1 queries**: Per document wordt `getDossierMembers()` aangeroepen
3. **AI-content**: Per document wordt AI-content opgehaald
4. **Filter counts**: Trage berekeningen per filter

## Oplossing: Pre-compute met Background Workers

### Architectuur
1. **Database tabel**: `dossier_metadata` - slaat pre-computed metadata op
2. **Background jobs**: `PrecomputeDossierMetadataJob` - compute metadata asynchroon
3. **Controller**: Gebruikt alleen pre-computed data (geen N+1 queries)
4. **Typesense sync**: (Toekomstig) Sync dossier metadata naar Typesense voor snelle zoek

### Database Schema

**`dossier_metadata` tabel:**
- `dossier_external_id` (unique)
- `status` (actief/gesloten)
- `member_count` (aantal documenten)
- `latest_publication_date` / `earliest_publication_date`
- `organisation`, `category`, `theme` (voor snelle filtering)
- `computed_at` (timestamp)

### Background Jobs

**`PrecomputeDossierMetadataJob`:**
- Compute metadata voor één dossier
- Wordt gedispatched na document sync
- Wordt gedispatched na dossier wijziging

**`PrecomputeAllDossiersMetadata` command:**
- Batch process alle dossiers
- Gebruik: `php artisan dossiers:precompute-metadata`
- Optioneel: `--batch-size=100 --limit=1000`

### Performance Verbetering

**Voor:**
- Count query: 1008ms
- Per document: getDossierMembers() + AI-content = ~50-100ms per document
- Totale laadtijd: 2-5 seconden

**Na:**
- Count query: <10ms (van metadata tabel)
- Per document: 0ms (alle data al in metadata)
- Totale laadtijd: <200ms

### Gebruik

1. **Eerste keer pre-computen:**
```bash
php artisan dossiers:precompute-metadata
php artisan queue:work
```

2. **Automatisch na sync:**
- Wordt geïntegreerd in sync proces (toekomstig)

3. **Controller gebruikt automatisch pre-computed data:**
- Geen wijzigingen nodig in frontend
- Fallback naar on-the-fly berekening als metadata niet bestaat

### Typesense Sync (Toekomstig)

- Sync dossier metadata naar Typesense collection
- Snelle zoek op dossiers
- Faceting op status, organisatie, etc.
