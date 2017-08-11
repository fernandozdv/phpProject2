<?php

require_once 'core/init.php';

// Set your secret key: remember to change this to your live secret key in production
// See your keys here: https://dashboard.stripe.com/account/apikeys
\Stripe\Stripe::setApiKey(STRIPE_PRIVATE);

// Token is created using Stripe.js or Checkout!
// Get the payment token ID submitted by the form:
$token = $_POST['stripeToken'];
//Resto de la solicitud post
$full_name=sanitize($_POST['full_name']);
$email=sanitize($_POST['email']);
$street=sanitize($_POST['street']);
$street2=sanitize($_POST['street2']);
$city=sanitize($_POST['city']);
$state=sanitize($_POST['state']);
$zip_code=sanitize($_POST['zip_code']);
$country=sanitize($_POST['country']);
$tax=sanitize($_POST['tax']);
$sub_total=sanitize($_POST['sub_total']);
$grand_total=sanitize($_POST['grand_total']);
$cart_id=sanitize($_POST['cart_id']);
$description=sanitize($_POST['description']);
$charge_amount=number_format($grand_total,2)*100;
$metadata=array(
  "cart_id"   =>$cart_id,
  "tax"       =>$tax,
  "sub_total" =>$sub_total
);
// Charge the user's card:
try{
$charge = \Stripe\Charge::create(array(
  "amount" => $charge_amount,
  "currency" => CURRENCY,
  "source" => $token,
  "description" => $description,
  "receipt_email" => $email,
  "metadata" => $metadata
));

$db->query("UPDATE cart SET paid=1 WHERE id='$cart_id'");
$db->query("INSERT INTO transactions
  (charge_id,cart_id,full_name,email,street,street2,city,state,zip_code,
    country,sub_total,tax,grand_total,description,txn_type) VALUES
    ('$charge->id','$cart_id','$full_name','$email','$street',
    '$street2','$city','$state','$zip_code','$country','$sub_total',
    '$tax','$grand_total','$description','$charge->object')");
//Elimina la cookie del carrito por venta realizada
setcookie(CART_COOKIE,'',1,"/",false);
include 'includes/head.php';
include 'includes/navigation.php';
include 'includes/headerpartial.php';
?>

  <h1 class="text-center text-success">Gracias!</h1>
  <p>Su tarjeta ha sido cargada exitosamente con <?=money($grand_total);?>.
  También recibió un mensaje en su correo .l. </p>
  <p>Tu número de recibo es <strong><?=$cart_id;?></strong></p>
  <address>
    <?=$full_name;?><br>
    <?=$street;?><br>
    <?=(($street2!='')?$street2.'<br>':'');?>
    <?=$city.', '.$state.', '.$zip_code;?><br>
    <?=$country;?><br>
  </address>

<?php

include 'includes/footer.php';
} catch(\Stripe\Error\Card $e){
  echo $e;
}

 ?>
