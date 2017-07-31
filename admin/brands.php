<?php
require_once '../core/init.php';
include 'includes/head.php';
include 'includes/navigation.php';
//obtener marcas
$sql="SELECT * FROM brand ORDER BY brand";
$results=$db->query($sql);
$errors=array();
//editar marcas
//Si existe y no es null, AND, no es vacio
if(isset($_GET['edit'])&&!empty($_GET['edit']))
{
  $edit_id=(int)$_GET['edit'];
  $edit_id=sanitize($edit_id);
  $sql2="SELECT * FROM brand WHERE id='$edit_id'";
  $edi_result=$db->query($sql2);
  $eBrand=mysqli_fetch_assoc($edi_result);
}
//eliminar marcas
//Si existe y no es null, AND, no es vacio
if(isset($_GET['delete'])&&!empty($_GET['delete']))
{
  $delete_id=(int) $_GET['delete'];
  $delete_id=sanitize($delete_id);
  $sql="DELETE FROM brand WHERE id='$delete_id'";
  $db->query($sql);
  header('Location: brands.php');
}
//agregar marcas de form
//Si existe y no es null
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
    if(isset($_GET['edit']))
    {
        $sql="SELECT * FROM brand WHERE brand='$brand' AND id!='$edit_id'";
    }
    $result=$db->query($sql);
    $count=mysqli_num_rows($result);
    if($count>0)
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
    if(isset($_GET['edit']))
    {
      $sql="UPDATE brand SET brand='$brand' WHERE id='$edit_id'";
    }
    $db->query($sql);
    $bnd=1;
    header('Location: brands.php');
  }
}

 ?>
<h2 class="text-center">Brands</h2><hr>
<!--Brand Form-->
<div class="text-center">
  <!-- Utiliza un botón solamente y crea un operador ternario para ajustar dependiendo si es para editar o agregar -->
  <form class="form-inline" action="brands.php<?=((isset($_GET['edit']))?'?edit='.$edit_id:'');?>" method="post">
    <div class="form-group">
      <?php
      $brand_value='';
      if(isset($_GET['edit'])){
        $brand_value=$eBrand['brand'];
      }elseif (isset($_POST['brand'])) {
        $brand_value=sanitize($_POST['brand']);
      }?>
      <label for="brand"><?=((isset($_GET['edit']))?'Editar: ':'Agregar: ');?></label>
      <input type="text" name="brand" id="brand" class="form-control" value="<?=$brand_value;?>">
      <input type="submit" name="add_submit" value="<?=((isset($_GET['edit']))?'Editar':'Agregar');?>" class="btn btn-success">
      <?php if(isset($_GET['edit'])){ ?>
        <a href="brands.php" class="btn btn-default">Cancel</a>
      <?php } ?>
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
