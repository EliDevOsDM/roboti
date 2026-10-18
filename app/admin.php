<?php
require_once __DIR__.'/view.php';
function admin_header(string $title):void { ?><!doctype html><html lang="es"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><meta name="robots" content="noindex,nofollow"><title><?=e($title)?> · Roboti Administración</title><link rel="icon" href="<?=asset('images/favicon.svg')?>"><link rel="stylesheet" href="<?=asset('css/site.css?v=20261017')?>"><link rel="stylesheet" href="<?=asset('css/admin.css')?>"></head><body class="admin-body"><header class="admin-header"><div class="wrap"><a class="brand" href="<?=url('admin/index.php')?>"><?=icon('robot','brand-mark')?><span>ROBOTI<small>ADMINISTRACIÓN</small></span></a><div><a href="<?=url('index.php')?>">Ver sitio <?=icon('arrow-up')?></a><?php if(!empty($_SESSION['admin_id'])):?><form method="post" action="<?=url('admin/logout.php')?>"><?=csrf_field()?><button type="submit">Cerrar sesión</button></form><?php endif;?></div></div></header><main class="wrap admin-main"><h1><?=e($title)?></h1><?php }
function admin_footer():void { echo '</main></body></html>'; }
function validate_course(array $p):array {
    $errors=[];
    $data=[];
    foreach(['slug','title','full_title','description','line','track','technology','schedule','price_period','materials','requirements','syllabus','start_date','location','image','status'] as $key) $data[$key]=trim(is_string($p[$key]??null)?$p[$key]:'');
    foreach(['title'=>200,'slug'=>190,'technology'=>60,'schedule'=>255,'price_period'=>100,'location'=>255,'description'=>10000,'full_title'=>1000,'materials'=>10000,'requirements'=>10000,'syllabus'=>20000] as $field=>$max) if(strlen($data[$field])>$max) $errors[]="El campo $field es demasiado largo (máximo $max bytes).";
    if($data['title']==='') $errors[]='Escribe el nombre del programa.';
    if(!preg_match('/^[a-z0-9]+(?:-[a-z0-9]+)*$/',$data['slug'])) $errors[]='La dirección debe contener letras minúsculas sin acentos, números y guiones.';
    if($data['description']==='') $errors[]='Escribe una descripción.';
    if(!isset(lines()[$data['line']])) $errors[]='Selecciona una línea válida.';
    if($data['line']==='edge' && !isset(tracks()[$data['track']])) $errors[]='Selecciona un nivel EDGE.';
    if($data['line']!=='edge') $data['track']='';
    if($data['technology']==='') $errors[]='Indica una tecnología.';
    if(!in_array($data['status'],['published','draft','archived'],true)) $errors[]='El estado no es válido.';
    foreach(['age_min','age_max'] as $key) {
        $value=trim((string)($p[$key]??''));$data[$key]=$value===''?null:filter_var($value,FILTER_VALIDATE_INT,['options'=>['min_range'=>3,'max_range'=>99]]);
        if($data[$key]===false) $errors[]='La edad debe estar entre 3 y 99 años.';
    }
    if($data['age_max']!==null && ($data['age_min']===null || $data['age_max']<$data['age_min'])) $errors[]='Revisa el rango de edad.';
    $data['duration_months']=filter_var($p['duration_months']??null,FILTER_VALIDATE_INT,['options'=>['min_range'=>1,'max_range'=>60]]);
    if($data['duration_months']===false) $errors[]='La duración debe ser de 1 a 60 meses.';
    foreach(['price','weekly_hours'] as $key){$raw=trim((string)($p[$key]??''));$data[$key]=$raw===''?null:filter_var($raw,FILTER_VALIDATE_FLOAT);if($data[$key]===false || ($data[$key]!==null && ($data[$key]<0 || $data[$key]>($key==='price'?999999:168)))) $errors[]='Revisa el importe o las horas semanales.';}
    if($data['price']!==null && $data['price_period']==='') $errors[]='Aclara si el precio es total, mensual o por módulo.';
    if($data['start_date']!==''){$d=DateTimeImmutable::createFromFormat('!Y-m-d',$data['start_date']);if(!$d || $d->format('Y-m-d')!==$data['start_date'])$errors[]='La fecha de inicio no es válida.';}
    if(!preg_match('/^[a-zA-Z0-9_-]+\.(webp|png|jpe?g)$/',$data['image']) || !is_file(ROOT.'/public/assets/images/'.$data['image'])) $errors[]='Selecciona una imagen disponible en assets/images.';
    $data['featured']=isset($p['featured'])?1:0;
    $data['sort_order']=filter_var($p['sort_order']??0,FILTER_VALIDATE_INT,['options'=>['min_range'=>0,'max_range'=>99999]]);
    if($data['sort_order']===false) $errors[]='El orden debe ser un número de 0 a 99999.';
    $data['updated_at']=date(DATE_ATOM);
    $data['search_text']=search_text($data['title'].' '.$data['full_title'].' '.$data['description'].' '.$data['technology']);
    return [$data,$errors];
}
