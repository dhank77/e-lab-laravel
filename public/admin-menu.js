const menuToggle = document.getElementById('admin-menu-toggle');
const adminMenu = document.getElementById('admin-menu');
const adminShell = document.getElementById('admin-shell');

function setMenuOpen(isOpen) {
    menuToggle.setAttribute('aria-expanded', String(isOpen));
    adminMenu.hidden = !isOpen;
    adminShell.classList.toggle('menu-collapsed', !isOpen);
}

menuToggle.hidden = false;

menuToggle.addEventListener('click', () => {
    setMenuOpen(adminMenu.hidden);
});

document.addEventListener('keydown', (event) => {
    if (event.key === 'Escape' && !adminMenu.hidden) {
        setMenuOpen(false);
        menuToggle.focus();
    }
});
