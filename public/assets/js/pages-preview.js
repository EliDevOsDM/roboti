// Solo se carga en la exportación pública para GitHub Pages.
(() => {
  const script = document.querySelector('script[src$="/assets/js/pages-preview.js"]');
  const root = new URL('../../', script.src);
  const form = document.querySelector('.filter-form');
  const selected = new URLSearchParams(location.search);
  if (form) {
    const grid = document.querySelector('.course-grid');
    const pagination = document.querySelector('.pagination');
    const count = document.querySelector('.catalog-toolbar strong');
    const normalize = text => String(text ?? '').normalize('NFD').replace(/[\u0300-\u036f]/g, '').toLowerCase();
    const track = selected.get('track') || '';
    if (track && !form.elements.namedItem('track')) {
      const input = document.createElement('input'); input.type = 'hidden'; input.name = 'track'; input.value = track; form.append(input);
    }
    for (const name of ['q', 'line', 'age', 'technology']) {
      const field = form.elements.namedItem(name);
      if (field) field.value = selected.get(name) || '';
    }
    fetch(new URL('assets/courses.json', root)).then(response => {
      if (!response.ok) throw new Error('No se pudo cargar el catálogo');
      return response.json();
    }).then(records => {
      const render = (page = 1, update = false) => {
        const params = new URLSearchParams(new FormData(form));
        for (const [key, value] of [...params]) if (!value) params.delete(key);
        const age = params.get('age');
        const query = normalize(params.get('q')).trim();
        const filtered = records.filter(c =>
          (!params.get('line') || c.line === params.get('line')) &&
          (!params.get('track') || c.track === params.get('track')) &&
          (!params.get('technology') || c.technology === params.get('technology')) &&
          (!age || (c.age_min !== null && Number(c.age_min) <= Number(age) && (c.age_max === null || Number(c.age_max) >= Number(age)))) &&
          (!query || normalize([c.title, c.full_title, c.description, c.technology].join(' ')).includes(query))
        );
        const pages = Math.max(1, Math.ceil(filtered.length / 12));
        page = Math.max(1, Math.min(pages, Number(page) || 1));
        count.textContent = filtered.length;
        document.querySelector('.empty-state')?.remove();
        // Markup generado y escapado en PHP durante la compilación; ningún dato del visitante se inserta como HTML.
        grid.innerHTML = filtered.slice((page - 1) * 12, page * 12).map(c => c.html).join('');
        if (!filtered.length) {
          const empty = document.createElement('div'); empty.className = 'empty-state';
          const title = document.createElement('h2'); title.textContent = 'Probemos otro camino';
          const text = document.createElement('p'); text.textContent = 'No encontramos propuestas con esos filtros. Prueba otra edad o tecnología.';
          empty.append(title, text); grid.before(empty);
        }
        pagination.replaceChildren();
        for (let n = 1; n <= pages; n++) {
          const link = document.createElement('a'); const target = new URL(location.href);
          const linkParams = new URLSearchParams(params); linkParams.set('page', n);
          target.search = linkParams.toString(); link.href = target; link.textContent = n;
          if (n === page) link.setAttribute('aria-current', 'page');
          link.addEventListener('click', event => { event.preventDefault(); render(n, true); form.scrollIntoView({block: 'start'}); });
          pagination.append(link);
        }
        params.set('page', page);
        if (update) history.pushState({}, '', location.pathname + '?' + params.toString());
      };
      render(selected.get('page') || 1);
      form.addEventListener('submit', event => { event.preventDefault(); render(1, true); });
      window.addEventListener('popstate', () => location.reload());
    }).catch(() => {
      const notice = document.createElement('p'); notice.className = 'catalog-note';
      notice.textContent = 'Mostramos el catálogo completo. Recarga la página para activar los filtros.';
      form.after(notice);
    });
  }
  const contact = document.querySelector('.contact-panel h2');
  if (contact && selected.get('programa')) contact.textContent = selected.get('programa').slice(0, 200);
})();
