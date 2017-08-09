<?php
  require_once $_SERVER['DOCUMENT_ROOT'].'/tutorial/phpProject2/core/init.php';
  $mode=sanitize($_POST['mode']);
  $edit_size=sanitize($_POST['edit_size']);
  $edit_id=sanitize($_POST['edit_id']);
  $cartQ=$db->query("SELECT * FROM cart WHERE id='$cart_id'");
  $result=mysqli_fetch_assoc($cartQ);
  $items=json_decode($result['items'],true);
  $updated_items=array();
  if($mode=='removeone')
  {
    foreach ($items as $item)
    {
      if($item['id']==$edit_id&&$item['size']==$edit_size)
      {
        $item['quantity']=$item['quantity']-1;
      }
      //No lo agrega si es que es 0
      if($item['quantity']>0)
      {
        $updated_items[]=$item;
      }
    }
  }
  if($mode=='addone')
  {
    foreach ($items as $item)
    {
      if($item['id']==$edit_id&&$item['size']==$edit_size)
      {
        $item['quantity']=$item['quantity']+1;
      }
      $updated_items[]=$item;
    }
  }
  //Si existe productos en el carrito, no vacío
  if(!empty($updated_items))
  {
    $json_updated=json_encode($updated_items);
    $db->query("UPDATE cart SET items='$json_updated' WHERE id='$cart_id'");
    $_SESSION['success_flash']='Tu carrito de compras ha sido actualizado!';
  }
  if(empty($updated_items))
  {
    $db->query("DELETE FROM cart WHERE id='$cart_id'");
    //Elimina la cookie
    setcookie(CART_COOKIE,'',1,"/",false);
  }
 ?>
