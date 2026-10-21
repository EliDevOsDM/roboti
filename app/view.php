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
function brand():void { ?>
<span class="brand-symbol"><?=icon('robot','brand-mark')?></span><span class="brand-word">roboti<span class="brand-dot">.</span><small>ACADEMY</small></span>
<?php }
function photo(string $name,string $alt,string $sizes='(max-width: 760px) 100vw, 50vw',bool $priority=false):void { ?>
<img src="<?=asset('images/'.$name)?>" srcset="<?=e(image_srcset($name))?>" sizes="<?=e($sizes)?>" alt="<?=e($alt)?>" width="1200" height="1600" decoding="async" <?= $priority?'fetchpriority="high"':'loading="lazy"'?>>
<?php }
function page_header(string $title='',string $active='inicio',string $description='Robótica, creatividad y aprendizaje práctico para niños, jóvenes y adultos. Descubre Roboti Academy y la metodología EDGE.'):void {
    $nav=['programas'=>['programas.php','Programas'],'edge'=>['metodologia.php','El método'],'club'=>['club.php','Roboticlub'],'games'=>['open-games.php','Open Games'],'nosotros'=>['nosotros.php','La academia']];
?>
<!doctype html><html lang="es"><head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><meta name="description" content="<?=e($description)?>"><meta name="theme-color" content="#092950">
<title><?=e($title ? $title.' · Roboti Academy':'Roboti Academy · Aprender es hacer')?></title>
<link rel="icon" href="<?=asset('images/favicon.svg?v=20261020-blue')?>" type="image/svg+xml">
<link rel="preload" href="<?=asset('fonts/space-grotesk.woff2')?>" as="font" type="font/woff2" crossorigin>
<link rel="stylesheet" href="<?=asset('css/site.css?v=20261020-footer')?>"><script src="<?=asset('js/site.js?v=20261020-blue')?>" defer></script>
</head><body class="page-<?=e($active)?>"><a class="skip-link" href="#contenido">Saltar al contenido</a>
<header class="site-header"><div class="wrap header-inner">
<a class="brand" href="<?=url('index.php')?>" aria-label="Roboti Academy, inicio"><?php brand();?></a>
<button class="menu-button" type="button" aria-label="Abrir menú" aria-controls="main-nav" aria-expanded="false"><span>Menú</span><?=icon('menu')?></button>
<nav id="main-nav" aria-label="Navegación principal"><?php foreach($nav as $key=>[$path,$label]):?><a href="<?=url($path)?>" <?=$key===$active?'aria-current="page"':''?>><?=e($label)?></a><?php endforeach;?><a class="nav-cta" href="<?=url('contacto.php')?>">Hablemos <?=icon('arrow-up')?></a><span class="mobile-nav-note">ROBÓTICA · TECNOLOGÍA · INNOVACIÓN<br>Guayaquil, Ecuador</span></nav>
</div></header><main id="contenido">
<?php }
function page_footer():void { ?>
</main><footer class="site-footer"><div class="wrap">
<div class="footer-top"><span class="eyebrow">EL PRÓXIMO PASO</span><h2>Hagamos espacio<br>para tus ideas.</h2><a class="button light" href="<?=url('programas.php')?>">Encuentra tu programa <?=icon('arrow-up')?></a></div>
<div class="footer-links"><p>Una academia para explorar,<br>construir y seguir aprendiendo.<br><span>Guayaquil, Ecuador</span></p><nav aria-label="Explorar Roboti"><a href="<?=url('programas.php')?>">Programas</a><a href="<?=url('metodologia.php')?>">Método EDGE</a><a href="<?=url('nosotros.php')?>">La academia</a></nav><nav aria-label="Comunidad y contacto"><a href="<?=url('club.php')?>">Roboticlub</a><a href="<?=url('open-games.php')?>">Open Games 2026</a><a href="<?=url('contacto.php')?>">Contacto <?=icon('arrow-up')?></a></nav></div>
<div class="footer-bottom"><span>© <?=date('Y')?> ROBOTI ACADEMY</span><span>APRENDER ES HACER.</span><a href="<?=url('privacidad.php')?>">Privacidad</a></div>
</div></footer></body></html>
<?php }
function course_card(array $c):void {
    $srcset=image_srcset(image_name($c));
?>
<article class="course-card">
<a class="course-art" href="<?=e(course_link($c))?>" tabindex="-1" aria-hidden="true"><img src="<?=e(image_url($c))?>"<?php if($srcset):?> srcset="<?=e($srcset)?>" sizes="(max-width: 580px) 96px, 160px"<?php endif;?> alt="" loading="lazy" decoding="async" width="1200" height="1600"></a>
<div class="course-body"><span class="course-kicker"><?=e(lines()[$c['line']])?><?= $c['track']?' / '.e(tracks()[$c['track']]):''?></span><h3><a href="<?=e(course_link($c))?>"><?=e($c['title'])?></a></h3><p><?=e($c['description'])?></p><div class="course-meta"><span><?=e(age_label($c))?></span><span><?=e($c['duration_months'])?> <?= (int)$c['duration_months']===1?'mes':'meses'?></span><span><?=e($c['technology'])?></span></div><div class="course-bottom"><small><?= $c['price']!==null?'$'.number_format((float)$c['price'],2).' '.e($c['price_period']):'Consultar inversión'?></small><a href="<?=e(course_link($c))?>" aria-label="Ver <?=e($c['title'])?>">Ver programa <?=icon('arrow-up')?></a></div></div>
</article>
<?php }
function breadcrumb(string $label):void { ?><div class="wrap breadcrumb"><a href="<?=url('index.php')?>">Inicio</a><span>/</span><span><?=e($label)?></span></div><?php }
function page_intro(string $eyebrow,string $title,string $body):void { ?><section class="page-intro wrap"><span class="eyebrow"><?=e($eyebrow)?></span><h1><?=$title?></h1><p><?=e($body)?></p></section><?php }
