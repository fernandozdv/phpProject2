<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/tutorial/phpProject2/core/init.php';
include 'includes/head.php';
include 'includes/navigation.php';
$result=$db->query("SELECT * FROM products WHERE deleted='1'");
if(isset($_GET['restaurar']))
{
  $id=$_GET['restaurar'];
  $desarchivar=$db->query("UPDATE products SET deleted='0' WHERE id='$id'");
  header('Location: archived.php');
}
 ?>
<h2 class="text-center">Productos archivados</h2><hr>
<table class="table table-bordered table-condensed table-striped">
  <thead>
    <th></th>
    <th>Producto</th>
    <th>Precio</th>
    <th>Categoría</th>
    <th>Destacado</th>
    <th>Vendidos</th>
  </thead>
  <tbody>
    <?php while ($archivado=mysqli_fetch_assoc($result)):
      $idcategoria=$archivado['categories'];
      $infoCategoria=$db->query("SELECT * FROM categories WHERE id='$idcategoria'");
      $hijo=mysqli_fetch_assoc($infoCategoria);
      $idPadre=$hijo['parent'];
      $infoPadre=$db->query("SELECT * FROM categories WHERE id='$idPadre'");
      $padre=mysqli_fetch_assoc($infoPadre);
      $categoria=$padre['category'].'-'.$hijo['category'];
      ?>
    <tr>
      <td>
        <a href="archived.php?restaurar=<?=$archivado['id'];?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-refresh"></span></a>
      </td>
      <td><?=$archivado['title'];?></td>
      <td><?=money($archivado['price']);?></td>
      <td><?=$categoria;?></td>
      <td><?=(($archivado['featured']=='1')?'Producto destacado':'Producto no destacado')?></td>
      <td>0</td>
    </tr>
  <?php endwhile; ?>
  </tbody>
</table>

 <?php include 'includes/footer.php' ?>
