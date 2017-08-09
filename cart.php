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
            <th>Subtotal</th>
          </thead>
          <tbody>
            <?php foreach($items as $item)
            {
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
                <td><?=$item['quantity'];?></td>
                <td><?=$item['size'];?></td>
                <td><?=money($item['quantity'] * $product['price']);?></td>
              </tr>
            <?php
              $i++;
              $item_count +=$item['quantity'];
              $sub_total+=($product['price']*$item['quantity']);
              }
              $grand_total=TAXRATETOTAL*$sub_total;
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
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                  <h4 class="modal-title" id="checkoutModalLabel">Modo de pago</h4>
                </div>
                <div class="modal-body">
                  ...
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                  <button type="button" class="btn btn-primary">Guardar cambios</button>
                </div>
              </div>
            </div>
          </div>

      <?php endif; ?>
  </div>
</div>

<?php
  include 'includes/footer.php';
 ?>
