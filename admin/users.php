<?php
//Indice de administración
require_once '../core/init.php';
//Verifica si no está logeado
if(!is_logged_in())
{
  //Redirige a pantalla de iniciar sesión
  login_error_redirect();
}
//Verifica si no tiene permisos de administrador
if(!has_permission('admin'))
{
  //Redirige a brands, no puede ingresar a admin/index.php
  permission_error_redirect('index.php');
}
include 'includes/head.php';
include 'includes/navigation.php';
//Eliminar usuario
if(isset($_GET['delete']))
{
  $delete_id=sanitize($_GET['delete']);
  if($delete_id==$user_data['id'])
  {
    header("Location: users.php");
  }
  else{
    $db->query("DELETE FROM users WHERE id='$delete_id'");
    $_SESSION['success_flash']='El usuario se eliminó con éxito';
    header("Location: users.php");
  }
}
//Botón agregar
if(isset($_GET['add']))
{
  $name=((isset($_POST['name']))?sanitize($_POST['name']):'');
  $email=((isset($_POST['email']))?sanitize($_POST['email']):'');
  $password=((isset($_POST['password']))?sanitize($_POST['password']):'');
  $confirm=((isset($_POST['confirm']))?sanitize($_POST['confirm']):'');
  $permissions=((isset($_POST['permissions']))?sanitize($_POST['permissions']):'');
  $errors=array();
  if($_POST)
  {
    $emailQuery=$db->query("SELECT * FROM users WHERE email='$email'");
    $emailCount=mysqli_num_rows($emailQuery);
    $required=array('name','email','password','confirm','permissions');
    $bnd=0;
    foreach ($required as $f) {
      if(empty($_POST[$f]))
      {
        $errors[]='Debe rellenar todos los campos';
        $bnd=1;
        break;
      }
    }
    if($bnd==0&&strlen($password)<6)
    {
      $errors[]='La contraseña debe tener de 6 dígitos a más';
    }elseif($password!=$confirm){
        $errors[]='Las contraseñas no coinciden';
    }
    if($bnd==0&&!filter_var($email,FILTER_VALIDATE_EMAIL))
    {
      $errors[]='El correo ingresado es inválido';
    }elseif($emailCount!=0){
      $errors[]='Ya existe un usuario registrado con ese correo';
    }
    if(!empty($errors))
    {
      echo display_errors($errors);
    }
    else{
      //agregar el usuario
      $hashed=password_hash($password,PASSWORD_DEFAULT);
      $db->query("INSERT INTO users(full_name,email,password,permissions) VALUES ('$name','$email','$hashed','$permissions')");
      $_SESSION['success_flash']='El usuario ha sido creado con éxito';
      header('Location: users.php');
    }

  }

  ?>
  <h2 class="text-center">Agregar usuario</h2><hr>
  <form action="users.php?add=1" method="post">
    <div class="row">
      <div class="form-group col-md-6">
        <label for="name">Nombre:</label>
        <input class="form-control" type="text" id="name" name="name" value="<?=$name;?>">
      </div>
      <div class="form-group col-md-6">
        <label for="email">Correo electrónico:</label>
        <input class="form-control" type="email" id="email" name="email" value="<?=$email;?>">
      </div>
    </div>
    <div class="row">
      <div class="form-group col-md-6">
        <label for="password">Contraseña:</label>
        <input class="form-control" type="password" id="password" name="password" value="<?=$password;?>">
      </div>
      <div class="form-group col-md-6">
          <label for="confirm">Confirmar contraseña:</label>
          <input class="form-control" type="password" id="confirm" name="confirm" value="<?=$confirm;?>">
      </div>
    </div>
    <div class="row">
      <div class="form-group col-md-6">
        <label for="email">Permisos:</label>
        <select class="form-control" name="permissions">
          <option value="" <?=(($permissions=='')?' selected':'');?>></option>
            <option value="editor" <?=(($permissions=='editor')?' selected':'');?>>Editor</option>
              <option value="admin,editor" <?=(($permissions=='admin,editor')?' selected':'');?>>Administrador</option>
        </select>
      </div>
      <div class="form-group col-md-6 text-right" style="margin-top:25px;">
        <input type="submit" class="btn btn-success" value="Agregar usuario">
        <a href="users.php" class="btn btn-default">Cancelar</a>
      </div>
    </div>


  </form>
<?php }else{
$userQuery=$db->query("SELECT * FROM users ORDER BY full_name");
 ?>
<h2 class="text-center">Usuarios</h2>
<a href="users.php?add=1" class="btn btn-success pull-right" id="add-user-btn">Agregar un nuevo usuario</a>
<hr>
<table class="table table-bordered table-striped table-condensed">
  <thead>
    <th></th>
    <th>Nombre</th>
    <th>Correo electrónico</th>
    <th>Fecha de ingreso</th>
    <th>Último acceso</th>
    <th>Permisos</th>
  </thead>
  <tbody>
    <?php while($user=mysqli_fetch_assoc($userQuery)):?>
      <tr>
        <td>
          <!-- Solo podra eliminar usuarios, pero no el mismo -->
          <?php if($user['id']!=$user_data['id']): ?>
            <a href="users.php?delete=<?=$user['id'];?>" class="btn btn-default btn-xs"><span class="glyphicon glyphicon-remove-sign"></span></a>
          <?php endif;  ?>
        </td>
        <td><?=$user['full_name'];?></td>
        <td><?=$user['email'];?></td>
        <td><?=pretty_date($user['join_date']);?></td>
        <td><?=(($user['last_login']=='0000-00-00 00:00:00')?'------':pretty_date($user['last_login']));?></td>
        <td><?=$user['permissions'];?></td>
      </tr>
    <?php endwhile;  ?>
  </tbody>
</table>
 <?php } include 'includes/footer.php'; ?>
