<?php
//Conexión a la base de datos
  $db=mysqli_connect('localhost','root','','tutorial');
  //Conexión entre mysqli y PHP, envío de datos
  $db->set_charset("utf8");
  //Si ocurre un error termina la conexión
  if(mysqli_connect_errno())
  {
    echo 'Fallo en la conección a la base de datos: '.mysqli_connect_error();
    //Equivalente a exit, no muestra nada
    die();
  }
  //Para hacer uso de sesiones
  session_start();
  //Llama al archivo de configuracion para la ruta absoluta
  require_once $_SERVER['DOCUMENT_ROOT'].'/tutorial/phpProject2/config.php';
  //Llama a helpers
  require_once BASEURL.'helpers/helpers.php';

  if(isset($_SESSION['SBUser']))
  {
    $user_id=$_SESSION['SBUser'];
    $query=$db->query("SELECT * FROM users WHERE id='$user_id'");
    $user_data=mysqli_fetch_assoc($query);
    $fn=explode(' ',$user_data['full_name']);
    $user_data['first']=$fn[0];
    $user_data['last']=$fn[1];
  }

  if(isset($_SESSION['success_flash']))
  {
    echo '<div class="bg-success"><h4 class="text-center text-success">'.$_SESSION['success_flash'].'</h4></div>';
    //una vez ha iniciado sesión, se destruye esa variable
    unset($_SESSION['success_flash']);
  }
  if(isset($_SESSION['error_flash']))
  {
    echo '<div class="bg-danger"><h4 class="text-center text-danger">'.$_SESSION['error_flash'].'</h4></div>';
    //una vez ha iniciado sesión, se destruye esa variable
    unset($_SESSION['error_flash']);
  }
  //session_destroy();
 ?>
