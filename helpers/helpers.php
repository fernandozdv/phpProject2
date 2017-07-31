<?php
//Alertas de error en formularios
  function display_errors($errors)
  {
    $display='<ul class="bg-danger">';
    foreach ($errors as $error)
    {
      $display.='<li class="text-danger">'.$error.'</li>';
    }
    $display.='</ul>';
    return $display;
  }
//Eliminación de ciertas entidades o caracteres en formularios < > / ' '
  function sanitize($dirty)
  {
    return htmlentities($dirty,ENT_QUOTES,"UTF-8");
  }
 ?>
