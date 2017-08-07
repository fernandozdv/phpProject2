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
if(isset($_GET['add'])||isset($_GET['edit']))
{
  $name=((isset($_POST['name']))&&!empty($_POST['name'])?sanitize($_POST['name']):'');
  $email=((isset($_POST['email']))&&!empty($_POST['email'])?sanitize($_POST['email']):'');
  $password=((isset($_POST['password']))&&!empty($_POST['password'])?sanitize($_POST['password']):'');
  $confirm=((isset($_POST['confirm']))&&!empty($_POST['confirm'])?sanitize($_POST['confirm']):'');
  $permissions=((isset($_POST['permissions']))&&!empty($_POST['permissions'])?sanitize($_POST['permissions']):'');
  $errors=array();

  if(isset($_GET['edit']))
  {
    $id_user=(int)$_GET['edit'];
    $usuarior=$db->query("SELECT * FROM users WHERE id='$id_user'");
    $usuario=mysqli_fetch_assoc($usuarior);
    $name=((isset($_POST['name']))&&$_POST['name']!=''?sanitize($_POST['name']):$usuario['full_name']);
    $email=((isset($_POST['email']))&&$_POST['email']!=''?sanitize($_POST['email']):$usuario['email']);
    $permissions=((isset($_POST['permissions']))&&$_POST['permissions']!=''?sanitize($_POST['permissions']):$usuario['permissions']);
  }

  if($_POST)
  {
    $emailQuery=$db->query("SELECT * FROM users WHERE email='$email'");
    $emailCount=mysqli_num_rows($emailQuery);
    $required=array('name','email','password','confirm','permissions');
    if(isset($_GET['edit']))
    {
      $required=array('name','email','permissions');
    }
    $bnd=0;
    foreach ($required as $f) {
      if(empty($_POST[$f]))
      {
        $errors[]='Debe rellenar todos los campos';
        $bnd=1;
        break;
      }
    }
    if(!isset($_GET['edit']))
    {
      if($bnd==0&&strlen($password)<6)
      {
        $errors[]='La contraseña debe tener de 6 dígitos a más';
      }elseif($password!=$confirm){
          $errors[]='Las contraseñas no coinciden';
      }
    }

    if($bnd==0&&!filter_var($email,FILTER_VALIDATE_EMAIL))
    {
      $errors[]='El correo ingresado es inválido';
    }elseif($emailCount!=0){
      if(isset($_GET['edit']))
      {
        if($email!=$usuario['email'])
        {
          $errors[]='Ya existe un usuario registrado con ese correo';
        }
      }else{
        $errors[]='Ya existe un usuario registrado con ese correo';
      }
    }
    if(!empty($errors))
    {
      echo display_errors($errors);
    }
    else{
      //agregar el usuario
      $hashed=password_hash($password,PASSWORD_DEFAULT);
      if(isset($_GET['edit']))
      {
        $db->query("UPDATE users SET full_name='$name',email='$email',permissions='$permissions' WHERE id='$id_user'");
        $_SESSION['success_flash']='El usuario ha sido modificado con éxito';
      }
      else{
          $db->query("INSERT INTO users(full_name,email,password,permissions) VALUES ('$name','$email','$hashed','$permissions')");
        $_SESSION['success_flash']='El usuario ha sido creado con éxito';
      }
      header('Location: users.php');
    }

  }

  ?>
  <h2 class="text-center"><?=((isset($_GET['edit']))?'Editar':'Agregar');?> usuario</h2><hr>
  <form action="users.php?<?=((isset($_GET['edit']))?'edit='.$_GET['edit']:'add=1');?>" method="post">
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
    <?php if(!isset($_GET['edit'])) :?>
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
    <?php  endif;?>
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
        <input type="submit" class="btn btn-success" value="<?=((isset($_GET['edit']))?'Editar':'Agregar');?> usuario">
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
            <a href="users.php?edit=<?=$user['id'];?>" class="btn btn-default btn-xs"><span class="glyphicon glyphicon-pencil"></span></a>
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
