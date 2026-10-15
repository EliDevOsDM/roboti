# Verificación de la primera versión

Fecha: 15 de octubre de 2026.

PHP 8.3, PDO SQLite. Los 20 archivos PHP pasan la comprobación de sintaxis. La conexión PDO para MySQL y el dump están preparados; este equipo no tiene un servidor MySQL disponible, por lo que no se ha verificado contra un servidor real.

La prueba `tests/integration.py` pasa 131 comprobaciones HTTP con una base temporal: inicialización idempotente, páginas públicas, filtros, búsqueda sin acentos, estado vacío, las 77 fichas, los 12 reglamentos, inexistentes 404, consultas preparadas, escape HTML, login, CSRF, creación, edición, publicación, borrador, archivo sin borrado y limitación de intentos de acceso. El catálogo local queda intacto y la prueba no deja usuarios de acceso.

Revisión en navegador a 1440 px y 390 px. Comprobación adicional de Inicio, Programas, EDGE, Roboticlub, Open Games, Nosotros, Contacto y login a 320 px: sin desbordamiento horizontal ni imágenes rotas detectadas. Menú móvil abre mediante botón semántico, actualiza aria-expanded y cierra con Escape. Filtro Essentials retorna cinco propuestas en el navegador. No se detectaron errores ni advertencias en consola.

No se enviaron mensajes externos ni inscripciones. Los canales oficiales de contacto siguen pendientes. Las imágenes de los cursos son ilustraciones conceptuales; los logos originales del club y torneo y los PDF están incluidos. Las discrepancias del material del torneo están documentadas y no se resolvieron por inferencia.
