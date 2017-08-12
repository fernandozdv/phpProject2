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
//Formato de precio, 2 decimales
  function money($number)
  {
    return '$'.number_format($number,2);
  }

  function login($user_id)
  {
    //Variable de sesión SBUser es igual al id ingresado
    $_SESSION['SBUser']=$user_id;
    //Para hacer uso de la variable $db que es global en init.php
    global $db;
    //dar formate fecha para $date
    $date=date("Y-m-d H:i:s");
    $db->query("UPDATE users SET last_login='$date' WHERE id='$user_id'");
    //Variable de sesión de confirmación de sesión
    $_SESSION['success_flash']='Te logeaste exitosamente!';
    header('Location: index.php');
  }

  function is_logged_in()
  {
    //Verifica si existe y porsiacaso si es un número ya que es el id
    if(isset($_SESSION['SBUser'])&&$_SESSION['SBUser']>0)
    {
      return true;
    }else{
      return false;
    }
  }

  function login_error_redirect($url='login.php')
  {
    $_SESSION['error_flash']='Necesita logearse.';
    header('Location: '.$url);
  }

  function has_permission($permission='admin')
  {
    global $user_data;
    $permissions=explode(',',$user_data['permissions']);
    //El parámetro true es para comparar que sea el mismo tipo de dato
    if(in_array($permission,$permissions,true))
    {
      return true;
    }
    return false;
  }

  function permission_error_redirect($url)
  {
    $_SESSION['error_flash']='No tienes permisos de administrador';
    header('Location: '.$url);
  }

  function pretty_date($date)
  {
    //Convierte a cadena y da formato
    //Mes en texto, día en número,año completo,h,m y periodo AM o PM
    return date('F d, Y h:i A',strtotime($date));
  }

  function get_category($child_id)
  {
    global $db;
    $id=sanitize($child_id);
    //Une categoría hijo con padre
    $query=$db->query("SELECT p.id AS 'pid',p.category AS 'parent', c.id AS 'cid', c.category AS 'child' FROM categories c INNER JOIN categories p ON c.parent=p.id WHERE c.id='$id'");
    $category=mysqli_fetch_assoc($query);
    return $category;
  }

  function sizesToArray($string)
  {
    $sizesArray=explode(',',$string);
    $returnArray=array();
    foreach($sizesArray as $sizes)
    {
      $s=explode(':',$sizes);
      $returnArray[]=array('size'=>$s[0],'quantity'=>$s[1]);
    }
    return $returnArray;
  }

  function sizesToString($sizes)
  {
    $sizeString='';
    foreach($sizes as $size)
    {
      $sizeString.=$size['size'].':'.$size['quantity'].',';
    }
    $trimmed=rtrim($sizeString,',');
    return $trimmed;
  }
 ?>
