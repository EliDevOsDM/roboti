# Verificación del rediseño — 24 de octubre de 2026

Las 131 comprobaciones de `tests/integration.py` pasan con una base temporal. La exportación valida 77 fichas, 12 reglamentos y 3555 enlaces y recursos locales, sin publicar archivos privados.

Revisión visual de la portada y secciones en escritorio, móvil de 390 px y tablet de 768 px. Comprobaciones de anchura de las ocho páginas públicas a 320 px: sin desbordamiento horizontal. Se ajustan el encuadre de las fotos y la distribución de las tarjetas en tablet.

Se verificaron el encabezado blanco al desplazar, apertura y cierre del menú móvil, Escape con retorno del foco, el acordeón nativo de preguntas frecuentes y la búsqueda de «Safari». El catálogo devuelve la ficha Explorer Safari Bot y su enlace de consulta conserva el nombre seleccionado. Las imágenes observadas cargan correctamente.

El backend PHP/MySQL y el panel administrativo conservan su implementación. No se ha probado una conexión MySQL real en este equipo. Los canales oficiales de contacto siguen pendientes y la web lo indica.

## Registro del rediseño anterior


Fecha: 19 de octubre de 2026.

Las 131 comprobaciones de `tests/integration.py` pasan después de reconstruir las plantillas públicas. La exportación valida 77 fichas, 12 reglamentos y 3376 enlaces y recursos locales, sin publicar archivos privados.

Revisión visual en escritorio y a 390 px. Comprobaciones de anchura a 768 y 320 px; las páginas públicas no presentan desplazamiento horizontal. Se corrigieron los títulos largos del catálogo, la composición de Roboticlub y el bloque de comunidad en móviles estrechos. El menú incluye bloqueo de desplazamiento, recorrido de foco y cierre con Escape. El aviso de Pages queda al final de la página.

Se verificaron búsqueda de «domotica», resultado filtrado, ficha individual y consulta con el programa seleccionado. Las siete fotografías se distribuyen según `ANALISIS-FOTOGRAFIAS.md`. Las fuentes se sirven localmente con sus licencias. El backend PHP/MySQL y sus condiciones de despliegue no cambian.

## Registro de la primera versión

Fecha: 15 de octubre de 2026.

PHP 8.3, PDO SQLite. Los 20 archivos PHP pasan la comprobación de sintaxis. La conexión PDO para MySQL y el dump están preparados; este equipo no tiene un servidor MySQL disponible, por lo que no se ha verificado contra un servidor real.

La prueba `tests/integration.py` pasa 131 comprobaciones HTTP con una base temporal: inicialización idempotente, páginas públicas, filtros, búsqueda sin acentos, estado vacío, las 77 fichas, los 12 reglamentos, inexistentes 404, consultas preparadas, escape HTML, login, CSRF, creación, edición, publicación, borrador, archivo sin borrado y limitación de intentos de acceso. El catálogo local queda intacto y la prueba no deja usuarios de acceso.

Revisión en navegador a 1440 px y 390 px. Comprobación adicional de Inicio, Programas, EDGE, Roboticlub, Open Games, Nosotros, Contacto y login a 320 px: sin desbordamiento horizontal ni imágenes rotas detectadas. Menú móvil abre mediante botón semántico, actualiza aria-expanded y cierra con Escape. Filtro Essentials retorna cinco propuestas en el navegador. No se detectaron errores ni advertencias en consola.

No se enviaron mensajes externos ni inscripciones. Los canales oficiales de contacto siguen pendientes. Las imágenes de los cursos son ilustraciones conceptuales; los logos originales del club y torneo y los PDF están incluidos. Las discrepancias del material del torneo están documentadas y no se resolvieron por inferencia.
