<?php
  require_once '../core/init.php';
  if(!is_logged_in())
  {
    header('Location: login.php');
  }
  include 'includes/head.php';
  include 'includes/navigation.php';

  //Orden completa
  if(isset($_GET['complete'])&&$_GET['complete']==1)
  {
    $cart_id=sanitize((int)$_GET['cart_id']);
    $db->query("UPDATE cart SET shipped=1 WHERE id='$cart_id'");
    $_SESSION['success_flash']='La orden ha sido completada!';
    header('Location: index.php');
  }
  //Detalles de la orden

  $txn_id=sanitize((int)$_GET['txn_id']);
  $txnQuery=$db->query("SELECT * FROM transactions WHERE id='$txn_id'");
  $txn=mysqli_fetch_assoc($txnQuery);
  $cart_id=$txn['cart_id'];
  $cartQ=$db->query("SELECT * FROM cart WHERE id='$cart_id'");
  $cart=mysqli_fetch_assoc($cartQ);
  $items=json_decode($cart['items'],true);
  $idArray=array();
  $products=array();
  foreach($items as $item)
  {
    $idArray[]=$item['id'];
  }
  //Aumentar comas separando elementos de un array
  // retornando el array con comas
  $ids=implode(',',$idArray);
  $productQ=$db->query("SELECT i.id AS 'id',i.title AS 'title',c.id AS 'cid',c.category AS 'child',p.category 'parent'
  FROM products i LEFT JOIN categories c ON i.categories =c.id
  LEFT JOIN categories p ON c.parent=p.id
  WHERE i.id IN($ids)");

  while($p=mysqli_fetch_assoc($productQ))
  {
    foreach($items as $item)
    {
      if($item['id']==$p['id'])
      {
        $x=$item;
        break;
      }
    }
    $products[]=array_merge($x,$p);
  }
 ?>

<h2 class="text-center">Productos solicitados</h2>
<table class="table table-condensed table-bordered table-striped">
  <thead>
    <th>Cantidad</th>
    <th>Nombre</th>
    <th>Categoría</th>
    <th>Tamaño</th>
  </thead>
  <tbody>
    <?php foreach($products as $product): ?>
      <tr>
        <td><?=$product['quantity'];?></td>
        <td><?=$product['title'];?></td>
        <td><?=$product['parent'].'-'.$product['child'];?></td>
        <td><?=$product['size'];?></td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>

<div class="row">
  <div class="col-md-6">
    <h3 class="text-center">Detalles de la orden</h3>
    <table class="table table-condensed table-striped table-bordered">
      <tbody>
        <tr>
          <td>Subtotal</td>
          <td><?=money($txn['sub_total']);?></td>
        </tr>
        <tr>
          <td>Impuesto</td>
          <td><?=money($txn['tax']);?></td>
        </tr>
        <tr>
          <td>Total</td>
          <td><?=money($txn['grand_total']);?></td>
        </tr>
        <tr>
          <td>Fecha</td>
          <td><?=pretty_date($txn['txn_date']);?></td>
        </tr>
      </tbody>
    </table>
  </div>
  <div class="col-md-6">
    <h3 class="text-center">Dirección de envío</h3>
    <address>
      <?=$txn['full_name'];?><br>
      <?=$txn['street'];?><br>
      <?=($txn['street2']!='')?$txn['street2'].'<br>':'';?>
      <?=$txn['city'].', '.$txn['state'].' '.$txn['zip_code'];?><br>
      <?=$txn['country'];?><br>
    </address>
  </div>
</div>
<div class="pull-right">
  <a href="index.php" class="btn btn-larg btn-default">Cancelar</a>
  <a href="orders.php?complete=1&cart_id=<?=$cart_id;?>" class="btn btn-primary btn-large">Orden completada</a>
</div>
<?php
  include 'includes/footer.php';
 ?>
