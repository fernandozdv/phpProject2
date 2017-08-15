<?php
//Indice de administración
require_once '../core/init.php';
//Verifica si no está logeado
if(!is_logged_in())
{
  header('Location: login.php');
}
include 'includes/head.php';
include 'includes/navigation.php';
//session_destroy();
 ?>
<!-- Órdenes -->
<?php
  $txnQuery="SELECT t.id,t.cart_id,t.full_name,t.description,t.txn_date,t.grand_total,c.items,c.paid,c.shipped
  FROM transactions t LEFT JOIN cart c ON t.cart_id=c.id
  WHERE c.paid=1 AND c.shipped=0
  ORDER BY t.txn_date";
  $txnResults=$db->query($txnQuery);
 ?>
 <div class="row">
    <div class="col-md-12">
      <h3 class="text-center">Órdenes a entregar</h3>
      <table class="table table-condensed table-bordered table-striped">
        <thead>
          <th></th>
          <th>Nombre</th>
          <th>Descripción</th>
          <th>Total</th>
          <th>Fecha</th>
        </thead>
        <tbody>
          <?php while($order=mysqli_fetch_assoc($txnResults)): ?>
            <tr>
              <td><a href="orders.php?txn_id=<?=$order['id'];?>" class="btn btn-xs btn-info">Detalles</a></td>
              <td><?=$order['full_name'];?></td>
              <td><?=$order['description'];?></td>
              <td><?=money($order['grand_total']);?></td>
              <td><?=pretty_date($order['txn_date']);?></td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>
  </div>
 <?php include 'includes/footer.php'; ?>
