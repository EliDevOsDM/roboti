<?php
declare(strict_types=1);
if(PHP_SAPI!=='cli') exit;
require dirname(__DIR__).'/app/bootstrap.php';
$sql="-- Roboti Academy: esquema e inventario inicial. No contiene credenciales.\n-- Importar en una base MySQL vacía; crea las tablas sin borrar datos existentes.\nSET NAMES utf8mb4;\n\n";
$sql.="CREATE TABLE IF NOT EXISTS courses (
 id INTEGER PRIMARY KEY AUTO_INCREMENT,
 slug VARCHAR(190) NOT NULL UNIQUE,
 title VARCHAR(200) NOT NULL,
 full_title TEXT NOT NULL,
 description TEXT NOT NULL,
 line VARCHAR(20) NOT NULL,
 track VARCHAR(30) NOT NULL DEFAULT '',
 age_min INTEGER NULL,
 age_max INTEGER NULL,
 technology VARCHAR(60) NOT NULL,
 duration_months INTEGER NOT NULL,
 weekly_hours DECIMAL(5,1) NULL,
 schedule VARCHAR(255) NOT NULL DEFAULT '',
 price DECIMAL(10,2) NULL,
 price_period VARCHAR(100) NOT NULL DEFAULT '',
 materials TEXT NOT NULL,
 requirements TEXT NOT NULL,
 syllabus TEXT NOT NULL,
 start_date VARCHAR(10) NOT NULL DEFAULT '',
 location VARCHAR(255) NOT NULL DEFAULT '',
 image VARCHAR(255) NOT NULL DEFAULT 'robot-hero.webp',
 status VARCHAR(20) NOT NULL DEFAULT 'draft',
 featured INTEGER NOT NULL DEFAULT 0,
 sort_order INTEGER NOT NULL DEFAULT 0,
 updated_at VARCHAR(30) NOT NULL,
 search_text TEXT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;\n\n";
$sql.="CREATE TABLE IF NOT EXISTS admins (id INTEGER PRIMARY KEY AUTO_INCREMENT, email VARCHAR(190) NOT NULL UNIQUE, password_hash VARCHAR(255) NOT NULL) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;\n";
$sql.="CREATE TABLE IF NOT EXISTS login_attempts (attempt_key VARCHAR(64) PRIMARY KEY, attempts INTEGER NOT NULL, last_attempt INTEGER NOT NULL) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;\n\n";
function literal(mixed $v):string { if($v===null)return 'NULL'; if(is_int($v)||is_float($v))return (string)$v;return "'".str_replace(['\\',"'"],["\\\\","''"],(string)$v)."'"; }
$rows=json_decode(file_get_contents(ROOT.'/database/courses.json'),true,512,JSON_THROW_ON_ERROR);
foreach($rows as $i=>$r){$r['sort_order']=$i;$r['updated_at']='2026-10-15T00:00:00-05:00';$r['search_text']=search_text($r['title'].' '.$r['full_title'].' '.$r['description'].' '.$r['technology']);$sql.='INSERT INTO courses ('.implode(',',array_keys($r)).') VALUES ('.implode(',',array_map('literal',array_values($r))).");\n";}
file_put_contents(ROOT.'/database/roboti.mysql.sql',$sql);
echo "database/roboti.mysql.sql generado con ".count($rows)." propuestas.\n";
