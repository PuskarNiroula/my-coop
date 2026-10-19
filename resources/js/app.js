
document.addEventListener('DOMContentLoaded', () => {
    const sidebar = document.getElementById('sidebar');
    const backdrop = document.getElementById('sidebarBackdrop');
    const mobileToggle = document.getElementById('mobileMenuToggle');
    const profileToggle = document.getElementById('profileMenuToggle');
    const profileMenu = document.getElementById('profileMenu');
    const menuGroups = [...document.querySelectorAll('.nav-group')];
    const menuButtons = [...document.querySelectorAll('[data-menu-toggle]')];

    const closeGroup = (group) => {
        group.classList.remove('is-open');

        const button = group.querySelector('[data-menu-toggle]');

        if (button) {
            button.setAttribute('aria-expanded', 'false');
        }
    };

    const openGroup = (group) => {
        group.classList.add('is-open');

        const button = group.querySelector('[data-menu-toggle]');

        if (button) {
            button.setAttribute('aria-expanded', 'true');
        }
    };

    menuButtons.forEach((button) => {
        button.addEventListener('click', () => {
            const group = button.closest('.nav-group');
            const shouldOpen = !group.classList.contains('is-open');

            menuGroups.forEach(closeGroup);

            if (shouldOpen) {
                openGroup(group);
            }
        });
    });

    const closeSidebar = () => {
        sidebar.classList.remove('mobile-open');
        backdrop.classList.remove('is-visible');
        document.body.style.overflow = '';
        mobileToggle?.setAttribute('aria-label', 'Open navigation');
    };

    const openSidebar = () => {
        sidebar.classList.add('mobile-open');
        backdrop.classList.add('is-visible');
        document.body.style.overflow = 'hidden';
        mobileToggle?.setAttribute('aria-label', 'Close navigation');
    };

    mobileToggle?.addEventListener('click', () => {
        if (sidebar.classList.contains('mobile-open')) {
            closeSidebar();
        } else {
            openSidebar();
        }
    });

    backdrop?.addEventListener('click', closeSidebar);

    sidebar.querySelectorAll('a').forEach((link) => {
        link.addEventListener('click', () => {
            if (window.innerWidth <= 760) {
                closeSidebar();
            }
        });
    });

    profileToggle?.addEventListener('click', (event) => {
        event.stopPropagation();

        const isVisible = profileMenu.classList.toggle('is-visible');

        profileToggle.setAttribute('aria-expanded', String(isVisible));
    });

    document.addEventListener('click', (event) => {
        if (
            profileMenu &&
            !profileMenu.contains(event.target) &&
            !profileToggle?.contains(event.target)
        ) {
            profileMenu.classList.remove('is-visible');
            profileToggle?.setAttribute('aria-expanded', 'false');
        }
    });

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') {
            closeSidebar();
            profileMenu?.classList.remove('is-visible');
            profileToggle?.setAttribute('aria-expanded', 'false');
        }
    });

    window.addEventListener('resize', () => {
        if (window.innerWidth > 760) {
            closeSidebar();
        }
    });

    menuGroups.forEach((group) => {
        if (group.querySelector('.submenu-link.active')) {
            openGroup(group);
        }
    });
});
