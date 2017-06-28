<?php
  $db=mysqli_connect('localhost','root','','tutorial');
  if(mysqli_connect_errno())
  {
    echo 'Fallo en la conección a la base de datos: '.mysqli_connect_error();
    die();
  }
 ?>
