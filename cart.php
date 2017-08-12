<?php
  require_once 'core/init.php';
  include 'includes/head.php';
  include 'includes/navigation.php';
  include 'includes/headerpartial.php';

  if($cart_id!='')
  {
    $cartQ=$db->query("SELECT * FROM cart WHERE id='$cart_id'");
    $result=mysqli_fetch_assoc($cartQ);
    $items=json_decode($result['items'],true);
    $i=1;
    $sub_total=0;
    $item_count=0;
    $grand_total=0;
  }
 ?>
<div class="row">
  <div class="col-md-12">
      <h2 class="text-center">Mi carrito de compras</h2><hr>
      <?php if($cart_id==''): ?>
        <div class="bg-danger">
          <p class="text-center text-danger">
            Tu carrito está vacío!
          </p>
        </div>
      <?php else: ?>
        <table class="table table-bordered table-condensed table-striped">
          <thead>
            <th>#</th>
            <th>Producto</th>
            <th>Precio</th>
            <th>Cantidad</th>
            <th>Tamaño</th>
            <th>Total</th>
          </thead>
          <tbody>
            <?php foreach($items as $item){
              $product_id=$item['id'];
              $productQ=$db->query("SELECT * FROM products WHERE id='$product_id'");
              $product=mysqli_fetch_assoc($productQ);
              $sArray=explode(',',$product['sizes']);
              foreach($sArray as $sizeString)
              {
                $s=explode(':',$sizeString);
                if($s[0]==$item['size'])
                {
                  $available=$s[1];
                }
              }
              ?>
              <tr>
                <td><?=$i;?></td>
                <td><?=$product['title'];?></td>
                <td><?=money($product['price']);?></td>
                <td>
                  <button class="btn btn-xs btn-default" onclick="update_cart('removeone','<?=$product['id'];?>','<?=$item['size'];?>');">-</button>
                  <?=$item['quantity'];?>
                  <?php if($item['quantity'] < $available): ?>
                    <button class="btn btn-xs btn-default" onclick="update_cart('addone','<?=$product['id'];?>','<?=$item['size'];?>');">+</button>
                  <?php else: ?>
                    <span class="text-danger">Máximo</span>
                  <?php  endif; ?>
                </td>
                <td><?=$item['size'];?></td>
                <td><?=money($item['quantity'] * $product['price']);?></td>
              </tr>
            <?php
              $i++;
              $item_count +=$item['quantity'];
              $grand_total+=($product['price']*$item['quantity']);
              }
              $sub_total=TAXRATETOTAL*$grand_total;
              $impuesto=TAXRATE*$sub_total;
              ?>
          </tbody>
        </table>
        <table class="table table-bordered table-condensed">
          <legend>Total</legend>
          <thead class="totals-table-header table-inverse">
            <th>Productos en total</th>
            <th>Subtotal</th>
            <th>Impuesto</th>
            <th>Total</th>
          </thead>
          <tbody>
            <tr>
              <td><?=$item_count;?></td>
              <td><?=money($sub_total);?></td>
              <td><?=money($impuesto);?></td>
              <th scope="row"><?=money($grand_total);?></th>
            </tr>
          </tbody>
        </table>

        <!-- Botón -->
          <button type="button" class="btn btn-primary btn-md pull-right" data-toggle="modal" data-target="#checkoutModal">
             <span class="glyphicon glyphicon-shopping-cart"></span> Pago>>
          </button>

          <!-- Modal -->
          <div class="modal fade" id="checkoutModal" tabindex="-1" role="dialog" aria-labelledby="checkoutModalLabel">
            <div class="modal-dialog modal-lg" role="document">
              <div class="modal-content">
              <form action="thankYou.php" method="post" id="payment-form">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="checkoutModalLabel">Información de pago</h4>
                  </div>
                  	<div class="modal-body">
                      <span class="bg-danger" id="payment-errors"></span>
                      <input type="hidden" name="tax" value="<?=$impuesto;?>">
                      <input type="hidden" name="sub_total" value="<?=$sub_total;?>">
                      <input type="hidden" name="grand_total" value="<?=$grand_total;?>">
                      <input type="hidden" name="cart_id" value="<?=$cart_id;?>">
                      <input type="hidden" name="description" value="<?=$item_count.' producto'.(($item_count>1)?'s':'').' de tiendita :v';?>">
                      <div id="step1" style="display:block">
                        <div class="row">
                          <div class="form-group col-md-6">
                            <label for="full_name">Nombre:</label>
                            <input type="text" class="form-control" name="full_name" id="full_name">
                          </div>
                          <div class="form-group col-md-6">
                            <label for="email">Correo electrónico:</label>
                            <input type="email" class="form-control" name="email" id="email">
                          </div>
                        </div>
                        <div class="row">
                          <div class="form-group col-md-6">
                            <label for="street">Dirección:</label>
                            <input type="text" class="form-control" name="street" id="street" data-stripe="address_line1">
                          </div>
                          <div class="form-group col-md-6">
                            <label for="street2">Dirección 2:</label>
                            <input type="text" class="form-control" name="street2" id="street2" data-stripe="address_line2">
                          </div>
                        </div>
                        <div class="row">
                          <div class="form-group col-md-6">
                            <label for="city">Ciudad:</label>
                            <input type="text" class="form-control" name="city" id="city" data-stripe="city">
                          </div>
                          <div class="form-group col-md-6">
                            <label for="state">Departamento:</label>
                            <input type="text" class="form-control" name="state" id="state" data-stripe="state">
                          </div>
                        </div>
                        <div class="row">
                          <div class="form-group col-md-6">
                            <label for="zip_code">Código postal:</label>
                            <input type="text" class="form-control" name="zip_code" id="zip_code" data-stripe="address_zip">
                          </div>
                          <div class="form-group col-md-6">
                            <label for="country">País:</label>
                            <input type="text" class="form-control" name="country" id="country" data-stripe="address_country">
                          </div>
                        </div>
                      </div>
                      <div id="step2" style="display:none">
                        <div class="row">
                          <div class="form-group col-md-3">
                              <label for="name">Nombre de la tarjeta:</label>
                              <input type="text" class="form-control" id="name" data-stripe="name">
                          </div>
                          <div class="form-group col-md-3">
                              <label for="number">Número de la tarjeta:</label>
                              <input type="text" class="form-control" id="number" data-stripe="number">
                          </div>
                          <div class="form-group col-md-2">
                              <label for="cvc">CVC:</label>
                              <input type="text" class="form-control" id="cvc" data-stripe="cvc">
                          </div>
                          <div class="form-group col-md-2">
                              <label for="name">Expira(Mes):</label>
                              <select class="form-control" id="exp-month" data-stripe="exp_month">
                                <option value=""></option>
                                <?php for($i=1;$i<13;$i++): ?>
                                  <option value="<?=$i;?>"><?=$i;?></option>
                                <?php endfor; ?>
                              </select>
                          </div>
                          <div class="form-group col-md-2">
                              <label for="exp-year">Expira(Año):</label>
                              <select class="form-control" id="exp-year" data-stripe="exp_year">
                                <option value=""></option>
                                <?php $yr=date("Y"); ?>
                                <?php for($i=0;$i<11;$i++): ?>
                                  <option value="<?=$yr+$i;?>"><?=$yr+$i;?></option>
                                <?php endfor; ?>
                              </select>
                          </div>
                        </div>
                      </div>
                    </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                  <button type="button" class="btn btn-primary" onclick="check_address();" id="next_button">Siguiente >></button>
                  <button type="button" class="btn btn-primary" onclick="back_address();" id="back_button" style="display:none"><< Anterior >></button>
                  <button type="submit" class="btn btn-primary" id="checkout_button" style="display:none">Verificar >></button>
                </div>
              </form>
              </div>
            </div>
          </div>

      <?php endif; ?>
  </div>
</div>

<script type="text/javascript">

  function back_address()
  {
    jQuery('#payment-errors').html("");
    jQuery('#step1').css("display","block");
    jQuery('#step2').css("display","none");
    //Botones alineados, no como bloque
    jQuery('#next_button').css("display","inline-block");
    jQuery('#back_button').css("display","none");
    jQuery('#checkout_button').css("display","none");
    jQuery('#checkoutModalLabel').html("Información de pago");

  }

  function check_address()
  {
    var data = {
      'full_name': jQuery('#full_name').val(),
      'email': jQuery('#email').val(),
      'street': jQuery('#street').val(),
      'street2': jQuery('#street2').val(),
      'city': jQuery('#city').val(),
      'state': jQuery('#state').val(),
      'zip_code': jQuery('#zip_code').val(),
      'country': jQuery('#country').val(),
    };
    jQuery.ajax({
      url:'/tutorial/phpProject2/admin/parsers/check_address.php',
      method: 'POST',
      data: data,
      success: function(data){
        if(data!='no-error')
        {
          jQuery('#payment-errors').html(data);
        }
        if(data=='no-error')
        {

          jQuery('#payment-errors').html("");
          jQuery('#step1').css("display","none");
          jQuery('#step2').css("display","block");
          jQuery('#next_button').css("display","none");
          jQuery('#back_button').css("display","inline-block");
          jQuery('#checkout_button').css("display","inline-block");
          jQuery('#checkoutModalLabel').html("Ingresa los detalles de tu tarjeta");
        }
      },
      error:function(){alert("Error,verificar dirección");}
    });
  }


  //NOTA: data-stripe en los campos permite extraer la información de formulario a Stripe
  //Identificar con Stripe para comunicarse
  Stripe.setPublishableKey('<?=STRIPE_PUBLIC;?>');

  function stripeResponseHandler(status,response){
    var $form=$('#payment-form');
    if(response.error){
      //Si ocurrió errores en el formulario lo mostrará en el div payment-errors
      $form.find('#payment-errors').text(response.error.message);
      //Activa los botones que fueron desactivados
      $form.find('button').prop('disabled',false);
    }else{
      //Obtiene un token
      var token=response.id;
      //Crea un campo oculto que será recibido por "THANKYOU.PHP"
      $form.append($('<input type="hidden" name="stripeToken" />').val(token));
      //Envía el formulario al servidor(thankyou.php)
      $form.get(0).submit();
    }
  };
  jQuery(function($){
    //Creación del token a partir del formulario a Stripe
    $('#payment-form').submit(function(event){
      var $form = $(this);
      //Desactiva botones temporalmente
      $form.find('button').prop('disabled',true);
      //Crea el toquen y obtiene la devolución a la llamada a Stripe, el token..
      Stripe.card.createToken($form,stripeResponseHandler);
      return false;
    })
  });
</script>

<?php
  include 'includes/footer.php';
 ?>
