<?php
  require_once $_SERVER['DOCUMENT_ROOT'].'/tutorial/phpProject2/core/init.php';
  $product_id=sanitize($_POST['product']);
  $size=sanitize($_POST['size']);
  $quantity=sanitize($_POST['quantity']);
  $available=sanitize($_POST['available']);
  $item=array();
  $item[]=array(
    'id'      =>$product_id,
    'size'    =>$size,
    'quantity'=>$quantity
  );

  //$domain=($_SERVER['HTTP_POST']!='localhost')?'.'.$_SERVER['HTTP_HOST']:false;
  $query=$db->query("SELECT * FROM products WHERE id='$product_id'");
  $product=mysqli_fetch_assoc($query);
  $_SESSION['success_flash']=$product['title'].' ha sido agregado al carrito.';

  //Verificar si existe la cookie del carrito
  if($cart_id!='')
  {
    //la cookie contiene solo el id del producto solicitado
    $cartQ=$db->query("SELECT * FROM cart WHERE id='$cart_id'");
    $cart=mysqli_fetch_assoc($cartQ);
    //devuelve array asociativo
    $previous_items=json_decode($cart['items'],true);
    $item_match=0;
    $new_items=array();
    //Itera
    foreach($previous_items as $pitem)
    {
      //Si es un producto ya agregado y es de la misma talla, solo aumenta la cantidad
      if($item[0]['id']==$pitem['id']&&$item[0]['size']==$pitem['size'])
      {
        $pitem['quantity']=$pitem['quantity']+$item[0]['quantity'];
        if($pitem['quantity']>$available)
        {
          $pitem['quantity']=$available;
        }
        $item_match=1;
      }
      $new_items[]=$pitem;
    }
    //Si no encontró ningún producto repetido, recién lo agrega junto a los anteriores
    if($item_match!=1)
    {
      //Une productos anteriores con el nuevo
      $new_items=array_merge($item,$previous_items);
    }

  $items_json=json_encode($new_items);
  $cart_expire=date("Y-m-d H:i:s",strtotime("+30 days"));
  $db->query("UPDATE cart SET items='$items_json',expire_date='$cart_expire' WHERE id='$cart_id'");
  //Elimina la cookie
  setcookie(CART_COOKIE,'',1, '/', false);
  //La crea nuevamente
  setcookie(CART_COOKIE,$cart_id,CART_COOKIE_EXPIRE,'/',false);
  }else{
    //agregar el carrito a la BD y crear la cookie
    $items_json=json_encode($item);
    //Tiempo de expiración en la BD
    $cart_expire=date("Y-m-d H:i:s",strtotime("+30 days"));
    $db->query("INSERT INTO cart(items,expire_date) VALUES ('$items_json','$cart_expire')");
    //Devuelve el id autogenerado que se utilizó en la última consulta
    $cart_id=$db->insert_id;
    //Nombre,valor,tiempo,ruta,(dominio opcional),false(securidad, true solo HTTPS y falso los dos)
    setcookie(CART_COOKIE, $cart_id, CART_COOKIE_EXPIRE, '/', false);
    }
 ?>
