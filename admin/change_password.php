<?php
  require_once $_SERVER['DOCUMENT_ROOT'].'/tutorial/phpProject2/core/init.php';
  if(!is_logged_in())
  {
    login_eror_redirect();
  }
  include 'includes/head.php';

  $hashed=$user_data['password'];
  $old_password=((isset($_POST['old_password']))?sanitize($_POST['old_password']):'');
  $old_password=trim($old_password);
  $password=((isset($_POST['password']))?sanitize($_POST['password']):'');
  $password=trim($password);
  $confirm=((isset($_POST['confirm']))?sanitize($_POST['confirm']):'');
  $confirm=trim($confirm);
  $user_id=$user_data['id'];
  $errors=array();
  /*Encriptación
  $hashed=password_hash("tamarenose27Z",PASSWORD_DEFAULT);
  echo $hashed;*/

 ?>
<style>
  body{
    background-image:url("/tutorial/phpProject2/images/headerlogo/background.png");
    /*Porcentaje de pantalla de imagen,imagen original responsive*/
    background-size: 100vw 100vh;
    background-attachment: fixed;
  }
</style>
 <div id="login-form">
   <div class="">
     <?php
      if($_POST)
      {
        //validación del formulario
        if(empty($_POST['old_password'])||empty($_POST['password'])||empty($_POST['confirm']))
        {
          if(empty($_POST['old_password']))
          {
            $errors[]='Ingrese la actual contraseña';
          }
          if(empty($_POST['password']))
          {
            $errors[]='Ingrese la nueva contraseña';
          }
          if(empty($_POST['confirm']))
          {
            $errors[]='Confirme la nueva contraseña';
          }
        }else{
          if(!password_verify($old_password,$hashed)){
              $errors[]="La contraseña actual ingresada es incorrecta. Ingrese otra vez.";
          }else{
              //Contraseña debe ser mayor a 6 caracteres
              if(strlen($password)<6)
              {
                $errors[]="La contraseña debe tener de 6 caracteres a más";
              }else{
                //verificar igualdad de contraseñas
                  if($password!=$confirm)
                  {
                    $errors[]="La nueva contraseña y la confirmación de la nueva contraseña no coinciden";
                  }
                }
              }
            }

        //Si hay errores
        if(!empty($errors))
        {
          echo display_errors($errors);
        }else{
          //cambio de contraseña
          $new_hashed=password_hash($password,PASSWORD_DEFAULT);
          $db->query("UPDATE users SET password='$new_hashed' WHERE id='$user_id'");
          $_SESSION['success_flash']='Tu contraseña ha sido cambiada con éxito';
          header('Location: index.php');
        }
      }
      ?>
   </div>
   <h2 class="text-center">Cambiar contraseña</h2>
   <form action="change_password.php" method="post">
     <div class="form-group">
       <label for="old_password">Contraseña anterior:</label>
       <input type="password" name="old_password" id="email" class="form-control" value="<?=$old_password;?>">
     </div>
     <div class="form-group">
       <label for="password">Contraseña nueva:</label>
       <input type="password" name="password" id="password" class="form-control" value="<?=$password;?>">
     </div>
     <div class="form-group">
       <label for="confirm">Confirmar contraseña nueva:</label>
       <input type="password" name="confirm" id="confirm" class="form-control" value="<?=$confirm;?>">
     </div>
     <div class="form-group">
       <input type="submit" class="btn btn-primary" value="Confirmar cambio">
       <a href="index.php" class="btn btn-default">Cancelar</a>
     </div>
   </form>
   <p class="text-right"><a href="/tutorial/phpProject2/index.php" alt="Tienda">Visitar tienda</a></p>
 </div>

<?php include 'includes/footer.php'; ?>
