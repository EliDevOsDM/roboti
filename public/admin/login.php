<?php
require dirname(__DIR__,2).'/app/admin.php';session_start_safe();if(!empty($_SESSION['admin_id']))redirect('admin/index.php');$error='';
if($_SERVER['REQUEST_METHOD']==='POST'){
 check_csrf();
 $email=strtolower(trim(is_string($_POST['email']??null)?$_POST['email']:''));$password=is_string($_POST['password']??null)?$_POST['password']:'';
 $key=hash('sha256',$_SERVER['REMOTE_ADDR']??'local');$q=db()->prepare('SELECT * FROM login_attempts WHERE attempt_key=?');$q->execute([$key]);$attempt=$q->fetch();
 if($attempt && (int)$attempt['attempts']>=10 && time()-(int)$attempt['last_attempt']<900){http_response_code(429);$error='Demasiados intentos. Espera 15 minutos antes de volver a intentar.';}
 else {
  $q=db()->prepare('SELECT * FROM admins WHERE email=?');$q->execute([$email]);$admin=$q->fetch();
  if(password_verify($password,$admin['password_hash']??'$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2uheWG/igi') && $admin){
   session_regenerate_id(true);$_SESSION['admin_id']=$admin['id'];$_SESSION['last_active']=time();$_SESSION['csrf']=bin2hex(random_bytes(32));
   $q=db()->prepare('DELETE FROM login_attempts WHERE attempt_key=?');$q->execute([$key]);redirect('admin/index.php');
  }
  $count=$attempt && time()-(int)$attempt['last_attempt']<900?(int)$attempt['attempts']+1:1;
  $driver=db()->getAttribute(PDO::ATTR_DRIVER_NAME);
  $sql=$driver==='mysql'?'INSERT INTO login_attempts (attempt_key,attempts,last_attempt) VALUES (?,?,?) ON DUPLICATE KEY UPDATE attempts=VALUES(attempts),last_attempt=VALUES(last_attempt)':'INSERT INTO login_attempts (attempt_key,attempts,last_attempt) VALUES (?,?,?) ON CONFLICT(attempt_key) DO UPDATE SET attempts=excluded.attempts,last_attempt=excluded.last_attempt';
  $q=db()->prepare($sql);$q->execute([$key,$count,time()]);$error='El correo o la contraseña no son correctos.';
 }
}
admin_header('Bienvenido de nuevo'); ?>
<div class="login-panel"><span class="eyebrow">GESTIÓN DE PROGRAMAS</span><h2>Tu academia,<br>al día.</h2><p>Accede para gestionar el catálogo de Roboti.</p><?php if($error):?><div class="form-error" role="alert"><?=e($error)?></div><?php endif;?><form method="post"><?=csrf_field()?><label for="email">Correo electrónico</label><input type="email" name="email" id="email" required autocomplete="username" maxlength="190"><label for="password">Contraseña</label><input type="password" name="password" id="password" required autocomplete="current-password"><button class="button full" type="submit">Entrar al panel <?=icon('arrow')?></button></form><small>El administrador se crea desde la consola del servidor. No hay credenciales predeterminadas.</small></div><?php admin_footer(); ?>
