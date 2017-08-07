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
 ?>
Usuarios xd
 <?php include 'includes/footer.php'; ?>
