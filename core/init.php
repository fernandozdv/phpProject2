<?php
//Conexión a la base de datos
  $db=mysqli_connect('localhost','root','','tutorial');
//Si ocurre un error termina la conexión
  if(mysqli_connect_errno())
  {
    echo 'Fallo en la conección a la base de datos: '.mysqli_connect_error();
    //Equivalente a exit, no muestra nada
    die();
  }
  //Llama al archivo de configuracion para la ruta absoluta
  require_once $_SERVER['DOCUMENT_ROOT'].'/tutorial/phpProject2/config.php';
  //Llama a helpers
  require_once BASEURL.'helpers/helpers.php';
 ?>
