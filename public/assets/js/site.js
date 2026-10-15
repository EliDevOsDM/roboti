const toggle = document.querySelector('.menu-button');
const menu = document.querySelector('#main-nav');
if (toggle && menu) {
  const close = () => { toggle.setAttribute('aria-expanded', 'false'); toggle.setAttribute('aria-label', 'Abrir menú'); menu.classList.remove('is-open'); };
  toggle.addEventListener('click', () => {
    const open = toggle.getAttribute('aria-expanded') !== 'true';
    toggle.setAttribute('aria-expanded', String(open));
    toggle.setAttribute('aria-label', open ? 'Cerrar menú' : 'Abrir menú');
    menu.classList.toggle('is-open', open);
  });
  document.addEventListener('keydown', e => { if (e.key === 'Escape' && menu.classList.contains('is-open')) { close(); toggle.focus(); } });
  document.addEventListener('click', e => { if (!menu.contains(e.target) && !toggle.contains(e.target)) close(); });
  window.matchMedia('(min-width: 1101px)').addEventListener('change', e => { if (e.matches) close(); });
}
