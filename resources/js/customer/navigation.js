document.addEventListener('DOMContentLoaded', () => {

    const menuButton =
        document.getElementById('customerMenuButton');

    const menuClose =
        document.getElementById('customerMenuClose');

    const mobileMenu =
        document.getElementById('customerMobileMenu');

    const menuOverlay =
        document.getElementById('customerMenuOverlay');


    if (
        !menuButton ||
        !mobileMenu ||
        !menuOverlay
    ) {
        return;
    }


    const openMenu = () => {

        mobileMenu.classList.add('show');
        menuOverlay.classList.add('show');

        menuButton.setAttribute(
            'aria-expanded',
            'true'
        );

        document.body.style.overflow = 'hidden';
    };


    const closeMenu = () => {

        mobileMenu.classList.remove('show');
        menuOverlay.classList.remove('show');

        menuButton.setAttribute(
            'aria-expanded',
            'false'
        );

        document.body.style.overflow = '';
    };


    menuButton.addEventListener(
        'click',
        openMenu
    );


    if (menuClose) {

        menuClose.addEventListener(
            'click',
            closeMenu
        );

    }


    menuOverlay.addEventListener(
        'click',
        closeMenu
    );


    mobileMenu
        .querySelectorAll('a')
        .forEach((link) => {

            link.addEventListener(
                'click',
                closeMenu
            );

        });


    document.addEventListener(
        'keydown',
        (event) => {

            if (
                event.key === 'Escape' &&
                mobileMenu.classList.contains('show')
            ) {
                closeMenu();
            }

        }
    );

});