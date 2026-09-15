document.addEventListener('DOMContentLoaded', () => {

    const mapsInput = document.getElementById('maps_link');
    const form = document.getElementById('addCustomerForm');

    if (!mapsInput || !form) {
        return;
    }

    /*
    |--------------------------------------------------------------------------
    | ELEMENT HASIL
    |--------------------------------------------------------------------------
    */

    const result = document.createElement('div');
    result.id = 'customerMapsResult';
    result.style.display = 'none';

    mapsInput.parentElement.appendChild(result);


    /*
    |--------------------------------------------------------------------------
    | CEK LINK GOOGLE MAPS
    |--------------------------------------------------------------------------
    */

    const isGoogleMapsLink = (value) => {
        try {
            const url = new URL(value);
            const hostname = url.hostname.toLowerCase();

            return (
                hostname === 'maps.app.goo.gl' ||
                hostname === 'goo.gl' ||
                hostname.includes('google.com')
            );

        } catch {
            return false;
        }
    };


    /*
    |--------------------------------------------------------------------------
    | TAMPILKAN PESAN
    |--------------------------------------------------------------------------
    */

    const showMessage = (message, type = 'error') => {

        result.innerHTML = `
            <div class="customer-maps-message customer-maps-${type}">
                ${message}
            </div>
        `;

        result.style.display = 'block';
    };


    /*
    |--------------------------------------------------------------------------
    | TAMPILKAN PREVIEW PETA
    |--------------------------------------------------------------------------
    */

    const showMapPreview = (latitude, longitude, mapsLink) => {

        const offset = 0.005;

        const bbox = [
            longitude - offset,
            latitude - offset,
            longitude + offset,
            latitude + offset
        ].join(',');

        const mapUrl =
            `https://www.openstreetmap.org/export/embed.html` +
            `?bbox=${bbox}` +
            `&layer=mapnik` +
            `&marker=${latitude},${longitude}`;


        result.innerHTML = '';


        const preview = document.createElement('div');
        preview.className = 'customer-maps-preview';


        const mapLink = document.createElement('a');

        mapLink.href = mapsLink;
        mapLink.target = '_blank';
        mapLink.rel = 'noopener noreferrer';
        mapLink.className = 'customer-maps-map-link';
        mapLink.title = 'Buka lokasi di Google Maps';


        const iframe = document.createElement('iframe');

        iframe.src = mapUrl;
        iframe.loading = 'lazy';
        iframe.title = 'Preview lokasi customer';
        iframe.className = 'customer-maps-iframe';


        const overlay = document.createElement('div');

        overlay.className = 'customer-maps-overlay';
        overlay.textContent = 'Buka di Google Maps';


        mapLink.appendChild(iframe);
        mapLink.appendChild(overlay);


        const locationInfo = document.createElement('div');

        locationInfo.className = 'customer-maps-location-info';


        const pin = document.createElement('span');

        pin.className = 'customer-maps-pin';
        pin.textContent = '📍';


        const infoText = document.createElement('div');


        const title = document.createElement('strong');

        title.textContent = 'Lokasi ditemukan';


        const coordinates = document.createElement('span');

        coordinates.textContent =
            `${latitude}, ${longitude}`;


        infoText.appendChild(title);
        infoText.appendChild(coordinates);

        locationInfo.appendChild(pin);
        locationInfo.appendChild(infoText);


        preview.appendChild(mapLink);
        preview.appendChild(locationInfo);

        result.appendChild(preview);

        result.style.display = 'block';
    };


    /*
    |--------------------------------------------------------------------------
    | PROSES LINK GOOGLE MAPS
    |--------------------------------------------------------------------------
    */

    const processMapsLink = async () => {

        const value = mapsInput.value.trim();


        // Kalau input dikosongkan
        if (!value) {
            result.style.display = 'none';
            result.innerHTML = '';
            return;
        }


        // Validasi awal
        if (!isGoogleMapsLink(value)) {

            showMessage(
                'Link yang dimasukkan bukan link Google Maps.',
                'error'
            );

            return;
        }


        /*
        |--------------------------------------------------------------------------
        | AMBIL CSRF TOKEN
        |--------------------------------------------------------------------------
        */

        const csrfToken =
            form.querySelector('input[name="_token"]')?.value;


        if (!csrfToken) {

            showMessage(
                'Token keamanan tidak ditemukan. Silakan muat ulang halaman.',
                'error'
            );

            return;
        }


        /*
        |--------------------------------------------------------------------------
        | TAMPILKAN STATUS MEMPROSES
        |--------------------------------------------------------------------------
        */

        result.innerHTML = `
            <div class="customer-maps-message customer-maps-loading">
                <span>Memeriksa lokasi Google Maps...</span>
            </div>
        `;

        result.style.display = 'block';


        /*
        |--------------------------------------------------------------------------
        | KIRIM LINK KE LARAVEL
        |--------------------------------------------------------------------------
        */

        try {

            const response = await fetch(
                '/staff/customers/resolve-map',
                {
                    method: 'POST',

                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },

                    body: JSON.stringify({
                        maps_link: value
                    })
                }
            );


            const data = await response.json();


            /*
            |--------------------------------------------------------------------------
            | JIKA GAGAL
            |--------------------------------------------------------------------------
            */

            if (!response.ok || !data.success) {

                showMessage(
                    data.message ||
                    'Lokasi Google Maps belum dapat diproses.',
                    'error'
                );

                return;
            }


            /*
            |--------------------------------------------------------------------------
            | JIKA BERHASIL
            |--------------------------------------------------------------------------
            */

            showMapPreview(
                data.latitude,
                data.longitude,
                value
            );


        } catch (error) {

            console.error(
                'Gagal memproses lokasi Google Maps:',
                error
            );

            showMessage(
                'Lokasi Google Maps tidak dapat diproses. Silakan coba lagi.',
                'error'
            );
        }
    };


    /*
    |--------------------------------------------------------------------------
    | SAAT LINK DITEMPEL
    |--------------------------------------------------------------------------
    */

    mapsInput.addEventListener('paste', () => {

        setTimeout(() => {
            processMapsLink();
        }, 100);

    });


    /*
    |--------------------------------------------------------------------------
    | SAAT INPUT BERUBAH
    |--------------------------------------------------------------------------
    */

    mapsInput.addEventListener('input', () => {

        const value = mapsInput.value.trim();

        if (!value) {
            result.style.display = 'none';
            result.innerHTML = '';
        }

    });


    /*
    |--------------------------------------------------------------------------
    | SAAT KELUAR DARI INPUT
    |--------------------------------------------------------------------------
    */

    mapsInput.addEventListener('blur', () => {
        processMapsLink();
    });

});