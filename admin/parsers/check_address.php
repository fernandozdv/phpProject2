<?php

  require_once $_SERVER['DOCUMENT_ROOT'].'/tutorial/phpProject2/core/init.php';
  $name=sanitize($_POST['full_name']);
  $email=sanitize($_POST['email']);
  $street=sanitize($_POST['street']);
  $street2=sanitize($_POST['street2']);
  $city=sanitize($_POST['city']);
  $state=sanitize($_POST['state']);
  $zip_code=sanitize($_POST['zip_code']);
  $country=sanitize($_POST['country']);
  $errors=array();
  $required=array(
    'full_name'=>'Nombre',
    'email'    =>'Correo electrónico',
    'street'   =>'Dirección',
    'city'     =>'Ciudad',
    'state'    =>'Departamento',
    'zip_code' =>'Código postal',
    'country' =>'País'
  );

  //Verificar los campos solicitados
  $bnd=0;
  foreach($required as $f=>$d)
  {
    if(empty($_POST[$f])||$_POST[$f]=='')
    {
      if($f=='email')
      {
        $bnd=1;
      }
      $errors[]='Debe ingresar el campo '.$d;
    }
  }

  //Verificar Correo
  if($bnd==0)
  {
    if(!filter_var($email,FILTER_VALIDATE_EMAIL))
    {
      $errors[]='Ingrese un correo válido.';
    }
  }

  if(!empty($errors))
  {
    echo display_errors($errors);
  }else{
    echo 'no-error';
  }

 ?>
