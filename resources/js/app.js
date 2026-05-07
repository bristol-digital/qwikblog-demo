import './bootstrap';

import Alpine from 'alpinejs';

// Expose for inline x-on / x-data and for any add-on plugins to register
// against (e.g. Alpine.plugin(...)) before start().
window.Alpine = Alpine;

Alpine.start();
