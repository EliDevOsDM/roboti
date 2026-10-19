const toggle = document.querySelector('.menu-button');
const menu = document.querySelector('#main-nav');
if (toggle && menu) {
  const close = () => { toggle.setAttribute('aria-expanded', 'false'); toggle.setAttribute('aria-label', 'Abrir menú'); menu.classList.remove('is-open'); document.body.classList.remove('menu-open'); };
  toggle.addEventListener('click', () => {
    const open = toggle.getAttribute('aria-expanded') !== 'true';
    toggle.setAttribute('aria-expanded', String(open));
    toggle.setAttribute('aria-label', open ? 'Cerrar menú' : 'Abrir menú');
    menu.classList.toggle('is-open', open);
    document.body.classList.toggle('menu-open', open);
  });
  document.addEventListener('keydown', e => { if (e.key === 'Escape' && menu.classList.contains('is-open')) { close(); toggle.focus(); } });
  document.addEventListener('click', e => { if (!menu.contains(e.target) && !toggle.contains(e.target)) close(); });
  menu.addEventListener('click', e => { if (e.target.closest('a')) close(); });
  document.addEventListener('keydown', e => {
    if (e.key !== 'Tab' || !menu.classList.contains('is-open')) return;
    const links = [...menu.querySelectorAll('a')];
    if (e.shiftKey && document.activeElement === toggle) { e.preventDefault(); links.at(-1)?.focus(); }
    else if (!e.shiftKey && document.activeElement === links.at(-1)) { e.preventDefault(); toggle.focus(); }
  });
  window.matchMedia('(min-width: 1101px)').addEventListener('change', e => { if (e.matches) close(); });
}
