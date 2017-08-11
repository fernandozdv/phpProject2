<?php
//Ruta absoluta del proyecto
  define('BASEURL',$_SERVER['DOCUMENT_ROOT'].'/tutorial/phpProject2/');
  define('CART_COOKIE','SBwi72UCklwiqzz2');
  //Tiempo de expiración, tiempo actual en segundos, más 30 días
  define('CART_COOKIE_EXPIRE',time()+(86400*30));
  //impuestoxd
  define('TAXRATETOTAL',100/118);
  define('TAXRATE',0.18);
  //Moneda usd: dolares
  define('CURRENCY','usd');
  define('CHECKOUTMODE','TEST');//Test prueba, LIVE real

  if(CHECKOUTMODE=='TEST')
  {
    //token privado
    define('STRIPE_PRIVATE','sk_test_plXBdKDo0aQjvF9PLtEKHFE9');
    //token de para publico
    define('STRIPE_PUBLIC','pk_test_Fd72v7UMIljkVoaMOzI6XrLt');
  }

  if(CHECKOUTMODE=='LIVE')
  {
    define('STRIPE_PRIVATE','');
    define('STRIPE_PUBLIC','');
  }
 ?>
