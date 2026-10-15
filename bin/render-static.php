<?php
declare(strict_types=1);
if(PHP_SAPI!=='cli')exit;
$root=dirname(__DIR__);
$mode=$argv[1]??'';
if($mode==='cards'){
    require $root.'/app/view.php';
    $output=[];
    foreach(courses() as $c){
        ob_start();course_card($c);$html=ob_get_clean();
        $output[]=['slug'=>$c['slug'],'title'=>$c['title'],'description'=>$c['description'],'full_title'=>$c['full_title'],'line'=>$c['line'],'track'=>$c['track'],'technology'=>$c['technology'],'age_min'=>$c['age_min'],'age_max'=>$c['age_max'],'html'=>$html];
    }
    echo json_encode($output,JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES|JSON_THROW_ON_ERROR);
    exit;
}
$page=$argv[2]??'index.php';
if(!in_array($page,['index.php','programas.php','programa.php','metodologia.php','club.php','open-games.php','nosotros.php','contacto.php','privacidad.php'],true))throw new InvalidArgumentException('Página no exportable.');
parse_str($argv[3]??'',$_GET);
require $root.'/public/'.$page;
