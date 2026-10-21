# Dirección visual de Roboti — 20 de octubre de 2026

## Concepto: un cuaderno de taller abierto

La interfaz usa una composición editorial inspirada en un taller: titulares amplios, fotografías de actividades, líneas de separación y etiquetas breves. La portada presenta «Aprender es hacer» sobre una franja de tres fotografías. Las rutas formativas aparecen como filas comparables y la metodología como un proceso. La navegación, el catálogo, las fichas y el pie fueron reconstruidos.

## Sistema visual

- Azul principal **#092950** (RGB 9, 41, 80), extraído de la palabra «ROBOTI» en el logo original de Open Games, por petición del cliente. Es el píxel azul más frecuente en esa zona del PNG; el archivo raster presenta pequeñas variaciones de tono. Hover **#061D39**, fondo profundo **#061B34** y superficie suave **#E7EDF4**. Botones, enlaces, titulares, iconos, panel administrativo y favicon comparten esta paleta.
- Fondo marfil **#F3F2EB**, texto **#282B26**, líneas **#D4D6CB** y superficies claras **#FFFEFA**.
- Acento lima **#DFE991** para el evento, letras EDGE y acciones sobre fondos oscuros.
- Space Grotesk variable para titulares y DM Sans variable para lectura, con licencias SIL OFL incluidas y archivos WOFF2 alojados localmente. Etiquetas cortas en fuente monoespaciada de sistema.
- Retícula de hasta 1280 px, separación base de 8 px, márgenes móviles de 18 px, secciones de 65–100 px.
- Imágenes rectangulares y bordes rectos. Iconos lineales. Interacciones de color y zoom discreto, respetando movimiento reducido.

## Composición y experiencia

- Portada: titular transversal, acción visible y franja de actividades; entrada a EDGE, Essentials y formación técnica; atajos por nivel; metodología; programas destacados; comunidad y evento.
- Programas: filtros laterales en escritorio, formulario compacto en móvil, entradas con fotografía, edad, duración y tecnología. Conserva búsqueda, filtros, paginación y el catálogo dinámico.
- Fichas: título de página, información del proyecto y panel de consulta con duración, inversión, horarios y requisitos.
- EDGE: explicación de sus cuatro etapas, fotografías relacionadas con exploración y construcción y navegación por niveles.
- Academia: misión, visión y fotografías de construcción, electrónica y formación tecnológica.
- Roboticlub: identidad original, comunidad, entrenamiento, competencia y requisitos de participación.
- Open Games: presentación como programa de evento, datos de participación y lista legible de las 11 categorías con sus reglamentos.
- Contacto y privacidad: contenido existente con la nueva jerarquía visual.
- Menú móvil: listado de navegación amplio, estado expandido accesible, cierre con Escape, control del foco y bloqueo del desplazamiento de fondo.

## Fotografías y marca

La asignación detallada de las siete fotos está en [ANALISIS-FOTOGRAFIAS.md](ANALISIS-FOTOGRAFIAS.md). Se distinguen iniciación con Bee-Bot, construcción guiada, electrónica y formación tecnológica. Se presentan como actividades de la academia, sin afirmar que documentan cada proyecto específico o una competición.

Los logos de Roboticlub, Open Games y el afiche se mantienen como originales. La marca tipográfica de cabecera sigue siendo provisional a la espera del manual de marca y del logo principal definitivo. Las versiones WebP de 600 y 1200 píxeles conservan colores y composición originales; CSS controla su encuadre de presentación.

## Implementación y publicación

Se mantienen PHP, PDO y MySQL, el panel administrativo y el inventario de 77 programas. GitHub Pages recibe la exportación de vistas públicas, con filtros en JavaScript, archivos locales de fuentes y los 12 reglamentos. El aviso de vista previa se coloca al final de las páginas.

El catálogo, las condiciones comerciales pendientes y las discrepancias documentales del torneo conservan su información. Los canales oficiales de contacto siguen configurándose en el entorno; no se inventan datos.

## Prompts de las ilustraciones anteriores

### Imagen 1

Use case: stylized-concept. Asset type: robotics academy website hero illustration. Create a high quality editorial 3D still life of an approachable small educational wheeled robot being assembled, in warm ivory, deep navy and burgundy. Friendly rounded rectangular head with two circular blue glass sensor eyes, visible circuit board, two black rubber wheels, burgundy aluminum panels, a few neatly arranged mechanical construction pieces and a short red cable. On a warm white studio tabletop with a solid warm ivory background, soft natural studio shadows. The robot is the main subject, three-quarter view facing left, occupies center 65% of square composition, fully inside image with generous margins. Sophisticated industrial design, tactile slightly matte plastic, real mechanical details. A charming maker-lab feeling suitable for children and adults, premium educational brand. No humans, no letters, no typography, no logos, no watermark. It is an illustrative concept, not an actual product or academy photograph.

### Imagen 2

Use case: stylized-concept. Asset: robotics education course card illustration. A premium 3D still life on a light powder-blue seamless studio backdrop, a small friendly modular building-block wheeled soccer robot made of unbranded navy, burgundy, cream and muted blue plastic bricks, two rubber wheels, simple exposed gears and one small white soccer ball at the front. Three-quarter view, fully visible centered with generous margins in a square image, soft shadows, tactile real materials, playful educational engineering, elegant clean composition. No people, no text, no logos or watermark. Illustrative concept rather than an actual product.

### Imagen 3

Use case: stylized-concept. Asset: electronics course card illustration for Roboti Academy. Premium 3D editorial still life of a compact blue unbranded microcontroller board with burgundy and cream connectors, three neat colorful jumper wires connected to a small sensor, a tiny white house model representing smart home automation, a small amber LED, on a pale sage studio tabletop and seamless background. A realistic but illustrative educational maker composition, centered square layout, fully in frame, soft studio shadows, refined tactile finish. No humans, no text, no logos, no brand names, no watermarks. Not a scientific wiring diagram.
