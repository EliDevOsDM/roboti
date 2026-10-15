<?php
declare(strict_types=1);
if (PHP_SAPI !== 'cli') exit;
define('INSTALLING', true);
require dirname(__DIR__) . '/app/bootstrap.php';
$pdo=db();
$mysql=$pdo->getAttribute(PDO::ATTR_DRIVER_NAME)==='mysql';
$id=$mysql?'INTEGER PRIMARY KEY AUTO_INCREMENT':'INTEGER PRIMARY KEY AUTOINCREMENT';
$pdo->exec("CREATE TABLE IF NOT EXISTS courses (
 id $id, slug VARCHAR(190) NOT NULL UNIQUE, title VARCHAR(200) NOT NULL, full_title TEXT NOT NULL,
 description TEXT NOT NULL, line VARCHAR(20) NOT NULL, track VARCHAR(30) NOT NULL DEFAULT '',
 age_min INTEGER NULL, age_max INTEGER NULL, technology VARCHAR(60) NOT NULL,
 duration_months INTEGER NOT NULL, weekly_hours DECIMAL(5,1) NULL, schedule VARCHAR(255) NOT NULL DEFAULT '',
 price DECIMAL(10,2) NULL, price_period VARCHAR(100) NOT NULL DEFAULT '', materials TEXT NOT NULL,
 requirements TEXT NOT NULL, syllabus TEXT NOT NULL, start_date VARCHAR(10) NOT NULL DEFAULT '',
 location VARCHAR(255) NOT NULL DEFAULT '', image VARCHAR(255) NOT NULL DEFAULT 'robot-hero.webp',
 status VARCHAR(20) NOT NULL DEFAULT 'draft', featured INTEGER NOT NULL DEFAULT 0, sort_order INTEGER NOT NULL DEFAULT 0,
 updated_at VARCHAR(30) NOT NULL, search_text TEXT NULL)");
$columns=$mysql?$pdo->query('SHOW COLUMNS FROM courses')->fetchAll(PDO::FETCH_COLUMN):array_column($pdo->query('PRAGMA table_info(courses)')->fetchAll(),'name');
if(!in_array('search_text',$columns,true))$pdo->exec('ALTER TABLE courses ADD COLUMN search_text TEXT NULL');
$pdo->exec("CREATE TABLE IF NOT EXISTS admins (id $id, email VARCHAR(190) NOT NULL UNIQUE, password_hash VARCHAR(255) NOT NULL)");
$pdo->exec('CREATE TABLE IF NOT EXISTS login_attempts (attempt_key VARCHAR(64) PRIMARY KEY, attempts INTEGER NOT NULL, last_attempt INTEGER NOT NULL)');
if ((int)$pdo->query('SELECT COUNT(*) FROM courses')->fetchColumn() === 0) {
    $records=json_decode(file_get_contents(ROOT.'/database/courses.json'),true,512,JSON_THROW_ON_ERROR);
    $pdo->beginTransaction();
    try {
        foreach($records as $i=>$record) {
            $record['sort_order']=$i; $record['updated_at']=date(DATE_ATOM);
            $record['search_text']=search_text($record['title'].' '.$record['full_title'].' '.$record['description'].' '.$record['technology']);
            $fields=array_keys($record);
            $q=$pdo->prepare('INSERT INTO courses ('.implode(',',$fields).') VALUES ('.implode(',',array_fill(0,count($fields),'?')).')');
            $q->execute(array_values($record));
        }
        $pdo->commit();
    } catch(Throwable $e) { $pdo->rollBack(); throw $e; }
    echo count($records)." propuestas importadas.\n";
} else echo "La base ya contiene cursos; se conservaron los datos.\n";
foreach($pdo->query('SELECT id,title,full_title,description,technology FROM courses WHERE search_text IS NULL')->fetchAll() as $c){
    $q=$pdo->prepare('UPDATE courses SET search_text=? WHERE id=?');
    $q->execute([search_text($c['title'].' '.$c['full_title'].' '.$c['description'].' '.$c['technology']),$c['id']]);
}
echo "Base de datos lista (".$pdo->getAttribute(PDO::ATTR_DRIVER_NAME).").\n";
