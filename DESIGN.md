# Dirección visual de Roboti — 24 de octubre de 2026

## Referencia y composición

El rediseño toma como referencia https://risingsunecuador.com.ec/: portada fotográfica de ancho completo, navegación integrada sobre la imagen, encabezado blanco al desplazar, mensajes breves, secciones amplias y tarjetas redondeadas. Se adapta al contenido, las fotografías y el azul de Roboti. La portada usa una fotografía real del taller; no se añade vídeo porque no se recibió material audiovisual.

## Sistema visual

- Azul principal **#092950** (RGB 9, 41, 80), extraído de la palabra «ROBOTI» en el logo original de Open Games. Hover **#153F70**, fondo profundo **#061B34**, superficie azul suave **#EDF4FA** y acento claro **#C7E5F6**.
- Fondos blancos, texto **#172B43**, texto secundario **#5F6C7B** y separadores **#DDE5ED**. Se retira el acento lima.
- DM Sans variable para titulares y lectura, alojada localmente con licencia SIL OFL. Los archivos de Space Grotesk se conservan como recurso anterior pero ya no se cargan.
- Contenido de hasta 1180 px, márgenes adaptables, secciones de 60–90 px, tarjetas con radios de 14–22 px y botones de 7 px.
- Fotografías con encuadres específicos, iconos lineales e interacciones discretas. Sin contenido oculto a la espera de animaciones. Se respeta la preferencia de movimiento reducido.

## Recorrido de la web

- Inicio: portada con dos acciones; datos básicos; presentación de la academia; EDGE, Essentials y formación técnica; niveles por edad; metodología; proyectos destacados; electrónica; comunidad y Open Games; preguntas frecuentes; llamada final.
- Programas: filtros superiores, tarjetas con fotografía, edad, duración y tecnología. La búsqueda, los filtros y la paginación se conservan.
- Fichas: información del proyecto, requisitos, materiales y panel de consulta con duración, horario, sede e inversión. El panel se apila en pantallas pequeñas.
- Método EDGE y academia: explicaciones, fotografías reales y navegación por niveles.
- Roboticlub y Open Games: logos originales, preparación para competencias y las 11 categorías con sus PDF.
- Contacto y privacidad: jerarquía y superficies coherentes con el resto del sitio.
- Pie compacto con enlaces útiles; no aparece la palabra «roboti» en tamaño gigante.

El menú móvil muestra enlaces amplios, informa su estado con aria-expanded, cierra con Escape y devuelve el foco al botón. Las preguntas frecuentes usan details/summary nativos. Se mantienen etiquetas de formularios, navegación por teclado y un enlace para saltar al contenido.

## Fotografías y marca

La clasificación de las siete imágenes está en [ANALISIS-FOTOGRAFIAS.md](ANALISIS-FOTOGRAFIAS.md). Se presentan como actividades de la academia, sin afirmar que documentan cada curso del inventario o una competición.

Los logos de Roboticlub, Open Games y el afiche conservan sus archivos originales. La marca de cabecera sigue siendo provisional, a la espera del manual de marca y del logo principal definitivo. Las versiones WebP de 600 y 1200 px conservan colores y composición originales; CSS controla el encuadre.

## Implementación y publicación

Se conservan PHP, PDO y MySQL, el panel administrativo y los 77 programas. GitHub Pages publica las vistas públicas exportadas, con filtros en JavaScript, fuentes locales y los 12 reglamentos. El aviso de vista previa está al final de las páginas.

Precios, horarios, contactos y otras condiciones pendientes no se inventan. La conexión MySQL real requiere un servidor compatible; las pruebas automatizadas utilizan una base SQLite temporal.
