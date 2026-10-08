const menuToggle = document.querySelector('[data-menu-toggle]');
const menu = document.querySelector('[data-menu]');

menuToggle?.addEventListener('click', () => {
    const isOpen = menu?.classList.toggle('open') ?? false;
    menuToggle.setAttribute('aria-expanded', String(isOpen));
});

const adminMenuToggle = document.querySelector('[data-admin-menu-toggle]');
const adminMenu = document.querySelector('#admin-navigation');

adminMenuToggle?.addEventListener('click', () => {
    const isOpen = adminMenu?.classList.toggle('open') ?? false;
    adminMenuToggle.setAttribute('aria-expanded', String(isOpen));
});

document.querySelectorAll('form[data-confirm]').forEach((form) => {
    form.addEventListener('submit', (event) => {
        if (!window.confirm(form.dataset.confirm ?? 'Continue?')) {
            event.preventDefault();
        }
    });
});
