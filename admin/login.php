<?php
  require_once $_SERVER['DOCUMENT_ROOT'].'/tutorial/phpProject2/core/init.php';
  include 'includes/head.php';
  $email=((isset($_POST['email']))?sanitize($_POST['email']):'');
  //eliminar espacios vacios
  $email=trim($email);
  $password=((isset($_POST['password']))?sanitize($_POST['password']):'');
  $password=trim($password);
  $errors=array();
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
        if(empty($_POST['email'])||empty($_POST['password']))
        {
          if(empty($_POST['email']))
          {
            $errors[]='Ingrese su correo';
          }
          if(empty($_POST['password']))
          {
            $errors[]='Ingrese su contraseña';
          }
        }else{
          if(!filter_var($email,FILTER_VALIDATE_EMAIL))
          {
            //Validar correo
            $errors[]="Ingrese un correo válido.";
          }else
          {
            //Verificar la existencia del correo en la BD
            $query=$db->query("SELECT * FROM users WHERE email='$email'");
            $user=mysqli_fetch_assoc($query);
            $userCount=mysqli_num_rows($query);
            if($userCount<1)
            {
              $errors[]='No existe un usuario registrado con el correo ingresado.';
            }else{
              //Contraseña debe ser mayor a 6 caracteres
              if(strlen($password)<6)
              {
                $errors[]="La contraseña debe tener de 6 caracteres a más";
              }else{
                //Verificar contraseña
                //Compara la contraseña ingresa con la de la BD
                //La contraseña de la BD se encuentra encriptada
                if(!password_verify($password,$user['password']))
                {
                  $errors[]="La contraseña ingresada es incorrecta. Ingrese otra vez.";
                }
              }
            }
          }
        }

        //Si hay errores
        if(!empty($errors))
        {
          echo display_errors($errors);
        }else{
          //logearse
          $user_id=$user['id'];
          //iniciar sesión
          login($user_id);
        }
      }
      ?>
   </div>
   <h2 class="text-center">Iniciar sesión</h2>
   <form action="login.php" method="post">
     <div class="form-group">
       <label for="email">Correo electrónico:</label>
       <input type="email" name="email" id="email" class="form-control" value="<?=$email;?>">
     </div>
     <div class="form-group">
       <label for="password">Contraseña:</label>
       <input type="password" name="password" id="password" class="form-control" value="<?=$password;?>">
     </div>
     <div class="form-group">
       <input type="submit" class="btn btn-primary" value="Iniciar sesión">
     </div>
   </form>
   <p class="text-right"><a href="/tutorial/phpProject2/index.php" alt="Tienda">Visitar tienda</a></p>
 </div>

<?php include 'includes/footer.php'; ?>
