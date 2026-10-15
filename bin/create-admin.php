<?php
declare(strict_types=1);
if(PHP_SAPI!=='cli') exit;
require dirname(__DIR__).'/app/bootstrap.php';
$email=trim($argv[1]??'');
if(!filter_var($email,FILTER_VALIDATE_EMAIL)) exit("Uso: php bin/create-admin.php correo@dominio.com\nIntroduce la contraseña por stdin, no como argumento.\n");
echo "Contraseña nueva (mínimo 12 caracteres): ";
$password=trim(fgets(STDIN));
if(strlen($password)<12) exit("La contraseña debe tener al menos 12 caracteres.\n");
$q=db()->prepare('INSERT INTO admins (email,password_hash) VALUES (?,?)');
$q->execute([strtolower($email),password_hash($password,PASSWORD_DEFAULT)]);
echo "Administrador creado. Accede a /admin/login.php\n";
