<?php
  $db=mysqli_connect('localhost','root','','tutorial');
  if(mysqli_connect_errno())
  {
    echo 'Fallo en la conección a la base de datos: '.mysqli_connect_error();
    die();
  }
  require_once $_SERVER['DOCUMENT_ROOT'].'/tutorial/phpProject2/config.php';
  require_once BASEURL.'helpers/helpers.php';
 ?>
