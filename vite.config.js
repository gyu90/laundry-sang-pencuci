import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                // APP
                'resources/css/app.css',
                'resources/js/app.js',

                // CUSTOMER
                'resources/css/customer.css',
                'resources/css/customer/account.css',
                'resources/js/customer/navigation.js',
                'resources/js/customer/services-carousel.js',
                'resources/js/customer/account.js',

                // LOGIN
                'resources/css/login.css',
                'resources/css/login-mobile.css',

                // OWNER
                'resources/css/owner.css',
                'resources/css/owner/staff.css',
                'resources/js/owner/staff/staff.js',

                // STAFF
                'resources/css/staff.css',
                'resources/css/staff/customers.css',
                'resources/css/staff/orders.css',
                'resources/css/staff/loyalty.css',
                'resources/css/staff/history.css',
                'resources/css/staff/services.css',
                'resources/css/staff/service-delete-modal.css',
                'resources/css/staff/service-deactivate-modal.css',
                'resources/css/staff/service-reactivate-modal.css',
                'resources/css/staff/service-package-deactivate-modal.css',

                'resources/js/staff/customer-maps.js',
                'resources/js/staff/customers.js',
                'resources/js/staff/dashboard.js',
                'resources/js/staff/loyalty.js',
                'resources/js/staff/service-delete-modal.js',
                'resources/js/staff/service-deactivate-modal.js',
                'resources/js/staff/service-package-deactivate-modal.js',
                'resources/js/staff/service-package-status-modal.js',
                'resources/js/staff/service-reactivate-modal.js',
                'resources/js/staff/services.js',

                'resources/js/staff/orders/create-order-modal.js',
                'resources/js/staff/orders/order-filter.js',
                'resources/js/staff/orders/order-status.js',
                'resources/js/staff/orders/payment-validation-modal.js',
            ],
            refresh: true,
        }),
        tailwindcss(),
    ],
});