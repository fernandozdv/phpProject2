<h3 class="text-center">Carrito</h3>
<div class="">
  <?php if(empty($cart_id)): ?>
    <p class="text-danger text-center">Carrito de compras vacío.</p>
  <?php else:
    $cartQ=$db->query("SELECT * FROM cart WHERE id='$cart_id'");
    $results=mysqli_fetch_assoc($cartQ);
    $items=json_decode($results['items'],true);
    $i=1;
    $grand_total=0;
    ?>

  <table class="table table-condensed" id="cart_widget">
    <tbody>
      <?php foreach($items as $item):
          $item_id=$item['id'];
          $productQ=$db->query("SELECT * FROM products WHERE id='$item_id'");
          $product=mysqli_fetch_assoc($productQ);
        ?>
        <tr>
          <td><?=$item['quantity'];?></td>
          <td><?=$product['title'];?></td>
          <td><?=money($item['quantity']*$product['price']);?></td>
        </tr>
      <?php
          $i++;
          $grand_total+=($item['quantity']*$product['price']);
          endforeach; ?>
          <tr>
            <td></td>
            <td>Total</td>
            <td><?=money($grand_total);?></td>
          </tr>
    </tbody>
  </table>
  <a href="cart.php" class="btn btn-xs btn-primary pull-right">Ver carrito</a>
  <div class="clearfix"></div>
  <?php endif; ?>
</div>
