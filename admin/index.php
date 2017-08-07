<?php
//Indice de administración
require_once '../core/init.php';
//Verifica si no está logeado
if(!is_logged_in())
{
  header('Location: login.php');
}
include 'includes/head.php';
include 'includes/navigation.php';
//session_destroy();
 ?>
Admiiiiiiiiiiiii
 <?php include 'includes/footer.php'; ?>
