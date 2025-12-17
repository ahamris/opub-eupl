import './bootstrap';
import '@tailwindplus/elements';

// Import Typesense Autocomplete
import { initTypesenseAutocomplete, destroyAutocomplete } from './typesense-autocomplete';

// Import Autocomplete theme styles
import '@algolia/autocomplete-theme-classic';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

// Expose autocomplete functions globally
window.initTypesenseAutocomplete = initTypesenseAutocomplete;
window.destroyAutocomplete = destroyAutocomplete;

Alpine.start();
