import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.store('modalManager', {
    bookingOpen: false,
    openBooking() {
        this.bookingOpen = true;
    },
    closeBooking() {
        this.bookingOpen = false;
    }
});

Alpine.start();
