<?php
require_once __DIR__.'/bootstrap.php';
function icon(string $name, string $class=''): string {
    $paths=[
    'arrow'=>'<path d="M5 12h14M13 6l6 6-6 6"/>',
    'arrow-up'=>'<path d="M6 18 18 6M6 6h12v12"/>',
    'robot'=>'<rect x="4" y="7" width="16" height="13" rx="4"/><path d="M12 3v4M8 12v2M16 12v2M9 17h6M1 11v5M23 11v5"/>',
    'code'=>'<path d="m8 6-6 6 6 6M16 6l6 6-6 6M14 3l-4 18"/>',
    'cube'=>'<path d="m12 2 9 5v10l-9 5-9-5V7Zm0 10 9-5M12 12 3 7M12 12v10M7.5 4.5l9 5"/>',
    'spark'=>'<path d="m12 2 2.7 7.3L22 12l-7.3 2.7L12 22l-2.7-7.3L2 12l7.3-2.7Z"/>',
    'clock'=>'<circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/>',
    'pin'=>'<path d="M20 10c0 6-8 12-8 12S4 16 4 10a8 8 0 1 1 16 0Z"/><circle cx="12" cy="10" r="2.5"/>',
    'check'=>'<path d="m5 12 4 4L19 6"/>',
    'search'=>'<circle cx="10.5" cy="10.5" r="6.5"/><path d="m16 16 5 5"/>',
    'download'=>'<path d="M12 3v12m-5-5 5 5 5-5M4 16v5h16v-5"/>',
    'trophy'=>'<path d="M7 3h10v6a5 5 0 0 1-10 0ZM7 5H3v3a4 4 0 0 0 4 4M17 5h4v3a4 4 0 0 1-4 4M12 14v6M7 21h10"/>',
    'users'=>'<circle cx="9" cy="7" r="3"/><path d="M3 21v-4a6 6 0 0 1 12 0v4M17 4a3 3 0 0 1 0 6M18 14a5 5 0 0 1 3 5v2"/>',
    'calendar'=>'<rect x="3" y="5" width="18" height="16" rx="2"/><path d="M7 2v6M17 2v6M3 11h18M7 15h2M12 15h2"/>',
    'mail'=>'<rect x="3" y="5" width="18" height="14" rx="2"/><path d="m3 6 9 7 9-7"/>',
    'menu'=>'<path d="M4 6h16M4 12h16M4 18h16"/>',
    'close'=>'<path d="m5 5 14 14M5 19 19 5"/>',
    ];
    return '<svg class="icon '.e($class).'" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">'.($paths[$name]??$paths['spark']).'</svg>';
}
function page_header(string $title='', string $active='inicio',string $description='Robótica, creatividad y aprendizaje práctico para niños, jóvenes y adultos. Descubre Roboti Academy y la metodología EDGE.'):void {
    $nav=['inicio'=>['index.php','Inicio'],'programas'=>['programas.php','Programas'],'edge'=>['metodologia.php','Método EDGE'],'club'=>['club.php','Roboticlub'],'games'=>['open-games.php','Open Games'],'nosotros'=>['nosotros.php','Nosotros']];
    ?><!doctype html><html lang="es"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><meta name="description" content="<?=e($description)?>"><meta name="theme-color" content="#8d2044"><title><?=e($title ? $title.' · Roboti Academy':'Roboti Academy · Donde las ideas cobran vida')?></title><link rel="icon" href="<?=asset('images/favicon.svg')?>" type="image/svg+xml"><link rel="stylesheet" href="<?=asset('css/site.css?v=20261017')?>"><script src="<?=asset('js/site.js')?>" defer></script></head><body><a class="skip-link" href="#contenido">Saltar al contenido</a><div class="topbar"><div class="wrap"><span>Pequeñas ideas. Grandes posibilidades.</span><a href="<?=url('open-games.php')?>">Roboti Open Games · 28 NOV 2026 <?=icon('arrow')?></a></div></div><header class="site-header"><div class="wrap header-inner"><a class="brand" href="<?=url('index.php')?>" aria-label="Roboti Academy, inicio"><?=icon('robot','brand-mark')?><span>ROBOTI<small>ACADEMY</small></span></a><button class="menu-button" type="button" aria-label="Abrir menú" aria-controls="main-nav" aria-expanded="false"><?=icon('menu')?></button><nav id="main-nav" aria-label="Navegación principal"><?php foreach($nav as $key=>[$path,$label]):?><a href="<?=url($path)?>" <?= $key===$active?'aria-current="page"':''?>><?=e($label)?></a><?php endforeach;?><a class="button small nav-cta" href="<?=url('contacto.php')?>">Conversemos <?=icon('arrow-up')?></a></nav></div></header><main id="contenido"><?php
}
function page_footer():void { ?>
</main><section class="footer-cta"><div class="wrap"><div><span class="eyebrow">EL SIGUIENTE GRAN INVENTO EMPIEZA AQUÍ</span><h2>La curiosidad ya la tienes.<br>Vamos a construir lo que sigue.</h2></div><a href="<?=url('programas.php')?>" class="button light">Encuentra tu programa <?=icon('arrow')?></a></div></section><footer class="site-footer"><div class="wrap footer-grid"><div><a class="brand" href="<?=url('index.php')?>"><?=icon('robot','brand-mark')?><span>ROBOTI<small>ACADEMY</small></span></a><p>Explorar, construir y transformar.<br>Aprendizaje que se pone en movimiento.</p><span class="footer-location"><?=icon('pin')?> Guayaquil, Ecuador</span></div><div><h3>Explora</h3><a href="<?=url('programas.php')?>">Nuestros programas</a><a href="<?=url('metodologia.php')?>">Metodología EDGE</a><a href="<?=url('nosotros.php')?>">Acerca de Roboti</a></div><div><h3>Ve más allá</h3><a href="<?=url('club.php')?>">Roboticlub</a><a href="<?=url('open-games.php')?>">Roboti Open Games</a><a href="<?=url('contacto.php')?>">Contacto</a></div><div><h3>¿Qué quieres crear?</h3><p>Encuentra una propuesta para tu edad y tus intereses.</p><a class="text-link" href="<?=url('programas.php')?>">Empieza a explorar <?=icon('arrow-up')?></a></div></div><div class="wrap footer-bottom"><span>© <?=date('Y')?> Roboti Academy</span><span>Robótica · Tecnología · Innovación</span><a href="<?=url('privacidad.php')?>">Privacidad</a></div></footer></body></html><?php }
function course_card(array $c):void {
    $theme=$c['line']==='adultos'?'navy':($c['track']==='builders'?'blue':($c['track']==='maker'?'mint':'rose'));
    ?><article class="course-card"><a class="course-art <?=$theme?>" href="<?=e(course_link($c))?>" tabindex="-1" aria-hidden="true"><span class="pill"><?=e(lines()[$c['line']])?><?= $c['track']?' / '.e(tracks()[$c['track']]):''?></span><img src="<?=e(image_url($c))?>" alt="" loading="lazy" width="500" height="380"><span class="art-caption"><?=e($c['technology'])?></span></a><div class="course-body"><span class="course-age"><?=e(age_label($c))?> <span>· <?=e($c['duration_months'])?> <?= (int)$c['duration_months']===1?'mes':'meses'?></span></span><h3><a href="<?=e(course_link($c))?>"><?=e($c['title'])?></a></h3><p><?=e($c['description'])?></p><div class="course-bottom"><span><?= $c['price']!==null?'$'.number_format((float)$c['price'],2).' '.e($c['price_period']):'Consulta la inversión'?></span><a href="<?=e(course_link($c))?>" aria-label="Ver <?=e($c['title'])?>"><?=icon('arrow-up')?></a></div></div></article><?php
}
function breadcrumb(string $label):void { ?><div class="wrap breadcrumb"><a href="<?=url('index.php')?>">Inicio</a><span>/</span><span><?=e($label)?></span></div><?php }
function page_intro(string $eyebrow,string $title,string $body):void { ?><section class="page-intro wrap"><span class="eyebrow"><?=e($eyebrow)?></span><h1><?=$title?></h1><p><?=e($body)?></p></section><?php }
