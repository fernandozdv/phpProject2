<?php
//Indice de administración
require_once '../core/init.php';
//Verifica si no está logeado
if(!is_logged_in())
{
  //Redirige a pantalla de iniciar sesión
  login_error_redirect();
}
include 'includes/head.php';
include 'includes/navigation.php';
//session_destroy();
 ?>
Admiiiiiiiiiiiii
 <?php include 'includes/footer.php'; ?>
