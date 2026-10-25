
document.addEventListener('DOMContentLoaded', () => {
    const sidebar = document.getElementById('sidebar');
    const backdrop = document.getElementById('sidebarBackdrop');
    const mobileToggle = document.getElementById('mobileMenuToggle');
    const profileToggle = document.getElementById('profileMenuToggle');
    const profileMenu = document.getElementById('profileMenu');

    const menuGroups = [...document.querySelectorAll('.nav-group')];
    const menuButtons = [...document.querySelectorAll('[data-menu-toggle]')];
    const submenuButtons = [...document.querySelectorAll('[data-submenu-toggle]')];

    const setExpanded = (button, expanded) => {
        button?.setAttribute('aria-expanded', String(expanded));
    };

    const closeGroup = (group) => {
        group.classList.remove('is-open');
        setExpanded(group.querySelector('[data-menu-toggle]'), false);
    };

    const openGroup = (group) => {
        group.classList.add('is-open');
        setExpanded(group.querySelector('[data-menu-toggle]'), true);
    };

    submenuButtons.forEach((button) => {
        button.addEventListener('click', () => {
            const group = button.closest('.submenu-group');

            if (!group) return;

            const isOpen = group.classList.toggle('is-open');
            setExpanded(button, isOpen);
        });
    });

    menuButtons.forEach((button) => {
        button.addEventListener('click', () => {
            const group = button.closest('.nav-group');

            if (!group) return;

            const shouldOpen = !group.classList.contains('is-open');

            menuGroups.forEach(closeGroup);

            if (shouldOpen) {
                openGroup(group);
            }
        });
    });

    const closeSidebar = () => {
        sidebar?.classList.remove('mobile-open');
        backdrop?.classList.remove('is-visible');
        document.body.style.overflow = '';
        setExpanded(mobileToggle, false);
        mobileToggle?.setAttribute('aria-label', 'Open navigation');
    };

    const openSidebar = () => {
        if (!sidebar) return;

        sidebar.classList.add('mobile-open');
        backdrop?.classList.add('is-visible');
        document.body.style.overflow = 'hidden';
        setExpanded(mobileToggle, true);
        mobileToggle?.setAttribute('aria-label', 'Close navigation');
    };

    mobileToggle?.addEventListener('click', () => {
        if (sidebar?.classList.contains('mobile-open')) {
            closeSidebar();
        } else {
            openSidebar();
        }
    });

    backdrop?.addEventListener('click', closeSidebar);

    sidebar?.querySelectorAll('a').forEach((link) => {
        link.addEventListener('click', () => {
            if (window.innerWidth <= 760) {
                closeSidebar();
            }
        });
    });

    profileToggle?.addEventListener('click', (event) => {
        event.stopPropagation();

        if (!profileMenu) return;

        const isVisible = profileMenu.classList.toggle('is-visible');
        setExpanded(profileToggle, isVisible);
    });

    document.addEventListener('click', (event) => {
        if (
            profileMenu &&
            !profileMenu.contains(event.target) &&
            !profileToggle?.contains(event.target)
        ) {
            profileMenu.classList.remove('is-visible');
            setExpanded(profileToggle, false);
        }
    });

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') {
            closeSidebar();
            profileMenu?.classList.remove('is-visible');
            setExpanded(profileToggle, false);

            document.querySelectorAll('.submenu-group.is-open').forEach((group) => {
                group.classList.remove('is-open');
                setExpanded(group.querySelector('[data-submenu-toggle]'), false);
            });
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

    document.querySelectorAll('.submenu-group').forEach((group) => {
        const activeLink = group.querySelector('.submenu-link.active');

        if (activeLink) {
            group.classList.add('is-open');
            setExpanded(group.querySelector('[data-submenu-toggle]'), true);
        }
    });
});
