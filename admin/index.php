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
  <div class="row">
    <!-- Ventas del mes -->
    <?php
      $thisYr=date("Y");
      $lastYr=$thisYr-1;
      $thisYrQ=$db->query("SELECT grand_total,txn_date FROM transactions WHERE YEAR(txn_date)='$thisYr'");
      $lastYrQ=$db->query("SELECT grand_total,txn_date FROM transactions WHERE YEAR(txn_date)='$lastYr'");
      //Ventas de este año
      $current=array();
      //Ventas del año anterior
      $last=array();
      $currentTotal=0;
      $lastTotal=0;
      //PARA ESTE MES
      while($x=mysqli_fetch_assoc($thisYrQ))
      {
        //Mes y darle formato de mes de la fecha de compra
        //Retorna un entero!!!
        $month=idate('m',strtotime($x['txn_date']));
        //Verifica si existe la clave $month en $current
        if(!array_key_exists($month,$current))
        {
          var_dump($current);
          //Agrega el mes como llave y almacena el total
          $current[$month]=$x['grand_total'];
        }else{
          echo ' no'.$month;
          //Si ya existe el mes como llave, suma el total para
          //calcular el total por mes
          $current[$month]+=$x['grand_total'];
        }
        $currentTotal+=$x['grand_total'];
      }

      //PARA EL MES PASADO
      while($y=mysqli_fetch_assoc($lastYrQ))
      {
        //Mes y darle formato de mes de la fecha de compra
        $month=idate('m',strtotime($y['txn_date']));
        //Verifica si existe la clave $month en $current
        if(!array_key_exists($month,$current))
        {
          //Agrega el mes como llave y almacena el total
          $last[$month]=$y['grand_total'];
        }else{
          //Si ya existe el mes como llave, suma el total para
          //calcular el total por mes
          $last[$month]+=$y['grand_total'];
        }
        $lastTotal+=$x['grand_total'];
      }

     ?>
    <div class="col-md-4">
      <h3 class="text-center">Ventas del mes</h3>
      <table class="table table-bordered table-condensed table-striped">
        <thead>
          <th></th>
          <th><?=$lastYr;?></th>
          <th><?=$thisYr;?></th>
        </thead>
        <tbody>
          <?php for($i=1;$i<=12;$i++):
            //Dar formato de mes desde 1,2...hasta 12
            $dt=DateTime::createFromFormat('m',$i);
            ?>
            <tr>
              <!-- Se mostrará como mes en texto completo en inglés -->
              <td><?=$dt->format("F");?></td>
              <td><?=((array_key_exists($i,$last))?money($last[$i]):money(0));?></td>
              <td><?=((array_key_exists($i,$current))?money($current[$i]):money(0));?></td>
            </tr>
          <?php endfor; ?>
            <tr>
              <td>Total</td>
              <td><?=$lastTotal;?></td>
              <td><?=$currentTotal;?></td>
            </tr>
        </tbody>
      </table>
    </div>
    <!-- Inventario -->
    <div class="col-md-8">

    </div>
  </div>
 <?php include 'includes/footer.php'; ?>
