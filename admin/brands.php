<?php
require_once '../core/init.php';
include 'includes/head.php';
include 'includes/navigation.php';
//obtener marcas
$sql="SELECT * FROM brand ORDER BY brand";
$results=$db->query($sql);
$errors=array();

//agregar marcas de form
if(isset($_POST['add_submit']))
{
  //verificar si está en blanco
  if($_POST['brand']=='')
  {
    $errors[].='Introduce una marca!';
  }
  else{
    //verificar si ya existe
    $brand=sanitize($_POST['brand']);
    $sql="SELECT * FROM brand WHERE brand='$brand'";
    $result=$db->query($sql);
    $count=mysqli_num_rows($result);
    if($count==1)
    {
      $errors[].='La marca '.$brand.' ya existe. Pruebe ingresando otra!.';
    }
  }
  //mostrar errores
  if(!empty($errors))
  {
    echo display_errors($errors);
  }
  else
  {
    //Agregar la marca a la base de datos
    $sql="INSERT INTO brand(brand) VALUES ('$brand')";
    $db->query($sql);
    $bnd=1;
    header('Location: brands.php');
  }
}

 ?>
<h2 class="text-center">Brands</h2><hr>
<!--Brand Form-->
<div class="text-center">
  <form class="form-inline" action="brands.php" method="post">
    <div class="form-group">
      <label for="brand">Add brand:</label>
      <input type="text" name="brand" id="brand" class="form-control" value="<?=((isset($_POST['brand']))?$_POST['brand']:'');?>">
      <input type="submit" name="add_submit" value="Add Brand" class="btn btn-success">
    </div>
  </form>
</div><hr>
<table class="table table-bordered table-striped table-auto table-condensed">
  <thead>
    <th></th><th>Brand</th><th></th>
  </thead>
  <tbody>
    <?php while ($brand=mysqli_fetch_assoc($results)) { ?>
    <tr>
      <td><a href="brands.php?edit=<?=$brand['id']?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-pencil"></span></a></td>
      <td><?=$brand['brand'] ?></td>
      <td><a href="brands.php?delete=<?=$brand['id']?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-remove-sign"></span></a></td>
    </tr>
    <?php } ?>
  </tbody>
</table>
 <?php include 'includes/footer.php'; ?>
