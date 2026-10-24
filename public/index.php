<?php
require dirname(__DIR__).'/app/view.php';
$featured=courses([],3);
page_header();
?>
<section class="home-hero" aria-labelledby="hero-title">
  <div class="hero-image"><?php photo('foto-taller-robotica.webp','Alumnos de Roboti construyendo robots en el taller','100vw',true);?></div>
  <div class="hero-shade"></div>
  <div class="wrap hero-content">
    <span class="eyebrow">ROBÓTICA Y TECNOLOGÍA EN GUAYAQUIL</span>
    <h1 id="hero-title">Aprender, crear.<br>Llegar más lejos.</h1>
    <p>Descubre lo que puedes construir con tus ideas.<br> Programas para niños, jóvenes y adultos.</p>
    <div class="hero-actions"><a class="button light" href="<?=url('programas.php')?>">Explorar programas <?=icon('arrow')?></a><a class="button glass" href="<?=url('contacto.php')?>">Consultar horarios <?=icon('arrow-up')?></a></div>
    <a class="hero-scroll" href="#academia">Conoce Roboti <span>↓</span></a>
  </div>
</section>
<div class="trust-strip"><div class="wrap"><span><?=icon('users')?> Desde los 3 años</span><span><?=icon('cube')?> Aprendizaje práctico</span><span><?=icon('pin')?> Guayaquil, Ecuador</span></div></div>

<section class="academy-intro section" id="academia"><div class="wrap intro-grid">
  <div class="intro-copy"><span class="eyebrow">BIENVENIDOS A ROBOTI ACADEMY</span><h2>La curiosidad es<br>el punto de partida.</h2><p>Un robot que se mueve. Un circuito que funciona. Una idea que encuentra su forma. Aquí, la tecnología se descubre con las manos.</p><p>Acompañamos a niños, jóvenes y adultos a desarrollar habilidades de robótica, programación y creatividad, a través de proyectos y desafíos.</p><a class="button" href="<?=url('nosotros.php')?>">Conoce nuestra academia <?=icon('arrow-up')?></a></div>
  <figure class="intro-photo"><?php photo('foto-robotica-acompanada.webp','Instructor guiando a un estudiante mientras programa y construye un robot');?><figcaption><?=icon('robot')?> Aprender con acompañamiento</figcaption></figure>
</div></section>

<section class="section wrap" id="programas"><div class="section-heading"><div><span class="eyebrow">UN CAMINO PARA CADA ETAPA</span><h2>Encuentra tu programa</h2></div><p>Desde los primeros descubrimientos hasta los proyectos de ingeniería. Elige cómo quieres empezar.</p></div>
  <div class="path-grid">
    <?php $paths=[['edge','EDGE','Desde los 3 años','Un recorrido por edades para explorar, construir y resolver desafíos.','foto-taller-robotica.webp','Estudiantes construyendo robots modulares'],['essentials','Essentials','Aprende los sábados','Un primer encuentro con la tecnología a través de retos prácticos.','foto-explorer-beebot.webp','Actividad de iniciación con Bee-Bot y bloques'],['adultos','Formación técnica','Jóvenes y adultos','Electrónica, programación y robótica para desarrollar nuevas habilidades.','foto-electronica-en-equipo.webp','Participantes trabajando sobre una placa electrónica']];foreach($paths as [$key,$title,$tag,$text,$image,$alt]):?>
    <a class="path-card" href="<?=url('programas.php?line='.$key)?>"><div class="path-photo"><?php photo($image,$alt,'(max-width: 640px) 100vw, 33vw');?></div><div class="path-content"><span class="path-tag"><?=$tag?></span><h3><?=$title?></h3><p><?=$text?></p><span class="path-link">Explorar programas <?=icon('arrow')?></span></div></a>
    <?php endforeach;?>
  </div>
  <div class="age-shortcuts"><span>Encuentra tu nivel EDGE</span><?php foreach([['explorer','3–7 años'],['builders','8–11 años'],['maker','12–14 años'],['engineering','15+ años']] as [$key,$age]):?><a href="<?=url('programas.php?line=edge&track='.$key)?>"><?=$age?> <?=icon('arrow')?></a><?php endforeach;?></div>
</section>

<section class="method-home section"><div class="wrap"><div class="section-heading"><div><span class="eyebrow">NUESTRA METODOLOGÍA</span><h2>Aprender haciendo.<br>Crecer en cada intento.</h2></div><p>El método EDGE conecta la curiosidad con la práctica. Cada etapa es una oportunidad para crear y encontrar soluciones.</p></div><div class="method-home-grid">
  <figure><?php photo('foto-explorer-espacio.webp','Grupo infantil explorando secuencias con un Bee-Bot en un tablero espacial');?><figcaption>Descubrir también es jugar.</figcaption></figure>
  <div class="learning-steps"><?php foreach([['01','Explorar','Observar, preguntar y descubrir cómo funcionan las cosas.'],['02','Desarrollar','Construir y programar para dar forma a una idea.'],['03','Resolver retos','Probar, colaborar y aprender con cada desafío.'],['04','Emprender','Presentar soluciones y compartir lo aprendido.']] as [$num,$title,$text]):?><div class="learning-step"><span><?=$num?></span><div><h3><?=$title?></h3><p><?=$text?></p></div></div><?php endforeach;?><a class="text-link" href="<?=url('metodologia.php')?>">Conoce el método EDGE <?=icon('arrow-up')?></a></div>
</div></div></section>

<section class="section wrap home-courses"><div class="section-heading"><div><span class="eyebrow">PROYECTOS PARA EMPEZAR</span><h2>Tu próxima creación</h2></div><a class="text-link" href="<?=url('programas.php')?>">Ver todos los programas <?=icon('arrow')?></a></div><div class="course-grid"><?php foreach($featured as $c)course_card($c);?></div></section>

<section class="experience-section"><div class="wrap experience-panel"><figure><?php photo('foto-circuitos.webp','Práctica de electrónica con cables, pulsadores y una protoboard');?></figure><div><span class="eyebrow">DEL CONOCIMIENTO A LA PRÁCTICA</span><h2>Ideas que se conectan.<br>Habilidades que crecen.</h2><p>La electrónica y la programación se entienden mejor cuando puedes experimentar. Descubre nuestras propuestas de formación técnica y sus requisitos.</p><a class="button light" href="<?=url('programas.php?line=adultos')?>">Explorar formación técnica <?=icon('arrow')?></a></div></div></section>

<section class="section wrap community-section"><div class="section-heading"><div><span class="eyebrow">LA TECNOLOGÍA NOS REÚNE</span><h2>Más allá del aula</h2></div><p>Un espacio para compartir lo aprendido, prepararse y descubrir nuevos desafíos.</p></div><div class="community-grid">
  <article class="club-home"><div class="club-home-logo"><img src="<?=asset('images/roboticlub.webp')?>" alt="Logo original de Roboticlub" width="1137" height="928" loading="lazy"></div><div><span class="eyebrow">ROBOTICLUB</span><h3>Crecer juntos.<br>Prepararse para competir.</h3><p>Comparte ideas y entrena para desafíos de robótica locales, nacionales e internacionales.</p><a class="text-link" href="<?=url('club.php')?>">Conoce el club <?=icon('arrow')?></a></div></article>
  <article class="games-home"><span class="event-date"><?=icon('calendar')?> 28 de noviembre de 2026</span><img src="<?=asset('images/open-games.webp')?>" alt="Roboti Open Games" width="1600" height="877" loading="lazy"><h3>Un encuentro.<br>11 desafíos.</h3><p>Primera edición en Guayaquil. Conoce las categorías y los reglamentos.</p><a class="button" href="<?=url('open-games.php')?>">Explorar Open Games <?=icon('arrow')?></a></article>
</div></section>

<section class="faq-section section"><div class="wrap faq-grid"><div><span class="eyebrow">TE ACOMPAÑAMOS A ELEGIR</span><h2>¿Por dónde<br>empezamos?</h2><p>Estas respuestas te ayudan a encontrar el siguiente paso. Para conocer horarios y disponibilidad, consulta el programa que te interesa.</p><a class="button" href="<?=url('contacto.php')?>">Consultar programas <?=icon('arrow-up')?></a></div><div class="faq-list">
  <details><summary>¿Desde qué edad se puede empezar? <?=icon('arrow')?></summary><p>Los programas EDGE comienzan desde los 3 años. El recorrido incluye Explorer (3–7), Builders (8–11), Maker (12–14) y Engineering (15+). Consulta los requisitos específicos de cada propuesta.</p></details>
  <details><summary>¿Necesito conocimientos previos? <?=icon('arrow')?></summary><p>Depende del programa. En cada ficha encontrarás los requisitos disponibles; consulta con la academia cuál es el punto de partida adecuado para ti.</p></details>
  <details><summary>¿Cómo consulto horarios y precios? <?=icon('arrow')?></summary><p>Elige un programa y utiliza «Consultar este programa». Confirma el próximo inicio, horario, inversión y disponibilidad del grupo antes de inscribirte.</p></details>
  <details><summary>¿Los materiales están incluidos? <?=icon('arrow')?></summary><p>Las condiciones cambian según la propuesta. Revisa la sección «Materiales y equipos» en la ficha y confirma con la academia qué está incluido y qué debes llevar.</p></details>
</div></div></section>

<section class="closing-cta"><div class="wrap"><div><span class="eyebrow">TU PRÓXIMO PROYECTO EMPIEZA AQUÍ</span><h2>Hagamos espacio para tus ideas.</h2></div><a class="button light" href="<?=url('programas.php')?>">Encuentra tu programa <?=icon('arrow')?></a></div></section>
<?php page_footer(); ?>
