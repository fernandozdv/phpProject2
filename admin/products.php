<?php
  require_once $_SERVER['DOCUMENT_ROOT'].'/tutorial/phpProject2/core/init.php';

  include 'includes/head.php';
  include 'includes/navigation.php';
  if(isset($_GET['add']))
  {
    $brandQuery=$db->query("SELECT * FROM brand");
    $parentQuery=$db->query("SELECT * FROM categories WHERE parent=0 ORDER BY category");
    if($_POST)
    {
      if(!empty($_POST['sizes']))
      {
        $sizeString=sanitize($_POST['sizes']);
        //Elimina la coma final
        $sizeString=rtrim($sizeString,',');
        //Divide los diferentes tamaños y cantidades con otras.
        $sizesArray=explode(',',$sizeString);
        $sArray=array();
        $qArray=array();
        foreach ($sizesArray as $ss)
        {
          //Divide los tamaños y sus cantidades
          $s=explode(':',$ss);
          //Almacena los tamaños, itera
          $sArray[]=$s[0];
          //Almacena la cantidad de los tamaños, itera
          $qArray[]=$s[1];
        }
      }else{
        $sizesArray=array();
      }
    }
    ?>
    <h2 class="text-center">Agregar un nuevo producto</h2>
    <form class="" action="products.php?add=1" method="post" enctype="multipart/form-data">
    <div class="row">
      <div class="form-group col-md-3">
        <label for="title">Título*:</label>
        <input type="text" name="title" class="form-control" id="title" value="<?=((isset($_POST['title']))?sanitize($_POST['title']):'');?>">
      </div>
      <div class="form-group col-md-3">
        <label for="brand">Marca*:</label>
        <select class="form-control" id="brand" name="brand">
          <option value="" <?=((isset($_POST['brand'])&&$_POST['brand']=='')?' selected':'')?>></option>
          <?php while($brand=mysqli_fetch_assoc($brandQuery)): ?>
            <option value="<?=$brand['id'];?>" <?=((isset($_POST['brand'])&&$_POST['brand']==$brand['id'])?' selected':'')?>><?=$brand['brand'];?></option>
          <?php endwhile; ?>
        </select>
      </div>
      <div class="form-group col-md-3">
        <label for="parent">Categoría padre</label>
        <select class="form-control" name="parent" id="parent">
          <option value=""<?=((isset($_POST['parent'])&&$_POST['parent']=='')?' selected':'')?>></option>
          <?php while ($parent=mysqli_fetch_assoc($parentQuery)):?>
            <option value="<?=$parent['id'];?>" <?=((isset($_POST['parent'])&&$_POST['parent']==$parent['id'])?' selected':'');?>><?=$parent['category'];?></option>
          <?php endwhile; ?>
        </select>
      </div>
      <div class="form-group col-md-3">
        <label for="child">Categoría hijo</label>
        <select id="child" name="child" class="form-control">
        </select>
      </div>
    </div>
    <div class="row">
      <div class="form-group col-md-3">
        <label for="price">Precio*:</label>
        <input type="text" name="price" id="price" class="form-control" value="<?=((isset($_POST['price']))?sanitize($_POST['price']):'');?>">
      </div>
      <div class="form-group col-md-3">
        <label for="list_price">Price de lista*:</label>
        <input type="text" name="list_price" id="list_price" class="form-control" value="<?=((isset($_POST['list_price']))?sanitize($_POST['list_price']):'');?>">
      </div>
      <div class="form-group col-md-3">
        <label>Cantidad y tamaños*:</label>
        <button class="btn btn-default form-control" onclick="jQuery('#sizesModal').modal('toggle');return false;" name="button">Cantidad y tamaños</button>
      </div>
      <div class="form-group col-md-3">
        <label for="sizes">Vista previa: Cantidad y tamaños</label>
        <input class="form-control" type="text" name="sizes" id="sizes" value="<?=((isset($_POST['sizes']))?$_POST['sizes']:'');?>" readonly>
      </div>
    </div>
    <div class="row">
      <div class="form-group col-md-6">
        <label for="photo">Imagen del producto:</label>
        <input type="file" name="photo" class="form-control" id="photo">
      </div>
      <div class="form-group col-md-6">
        <label for="description">Descripción:</label>
        <textarea name="description" id="description" class="form-control" rows="6"><?=((isset($_POST['description']))?sanitize($_POST['description']):'');?></textarea>
      </div>
    </div>
    <div class="form-group pull-right">
      <input type="submit" class="form-control btn btn-success pull-right" value="Agregar un producto">
    </div><div class="clearfix"></div>
    </form>

    <!-- Modal -->
    <div class="modal fade" id="sizesModal" tabindex="-1" role="dialog" aria-labelledby="sizesModalLabel">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="sizesModalLabel">Tamaños y cantidad</h4>
          </div>
        <div class="modal-body">
          <div class="container-fluid">
            <?php for ($i=1; $i <= 12; $i++):?>
            <div class="row">
              <div class="form-group col-md-8">
                <label for="size<?=$i;?>">Tamaño:</label>
                <input type="text" name="size<?=$i;?>" class="form-control" id="size<?=$i;?>" value="<?=((!empty($sArray[$i-1]))?$sArray[$i-1]:'')?>">
              </div>
              <div class="form-group col-md-4">
                <label for="qty<?=$i;?>">Cantidad:</label>
                <input type="number" name="qty<?=$i;?>" class="form-control" id="qty<?=$i;?>" value="<?=((!empty($qArray[$i-1]))?$qArray[$i-1]:'0')?>" min="0">
              </div>
            </div>
            <?php endfor; ?>
          </div>
        </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary" onclick="updateSizes();jQuery('#sizesModal').modal('toggle');return false;">Save changes</button>
          </div>
        </div>
      </div>
    </div>
<?php }
  else{
  /*Productos que no han sido eliminados lógicamente*/
  $sql="SELECT * FROM products WHERE deleted=0";
  $presults=$db->query($sql);
  //Destacados
  if(isset($_GET['featured']))
  {
    $id=(int)$_GET['id'];
    $featured=(int)$_GET['featured'];
    //Actualiza el valor a destacado/no destacado cuando se da click en el botón
    $featuredSql="UPDATE products SET featured='$featured' WHERE id='$id'";
    $db->query($featuredSql);
    header('Location: products.php');
  }
 ?>

<h2 class="text-center">Products</h2>
<a href="products.php?add=1" class="btn btn-success pull-right" id="add-product-btn">Add Product</a>
<div class="clearfix"></div><hr>
<table class="table table-bordered table-condensed table-striped">
  <thead>
    <th></th>
    <th>Product</th>
    <th>Price</th>
    <th>Category</th>
    <th>Featured</th>
    <th>Sold</th>
  </thead>
  <tbody>
    <?php while($product=mysqli_fetch_assoc($presults)):
      //Buscar el nombre de la categoría a la que pertenece y unirle con su categoría padre
      $childID=$product['categories'];
      $catSql="SELECT * FROM categories WHERE id='$childID'";
      $result=$db->query($catSql);
      $child=mysqli_fetch_assoc($result);
      $parentID=$child['parent'];
      $pSql="SELECT * FROM categories WHERE id='$parentID'";
      $presult=$db->query($pSql);
      $parent=mysqli_fetch_assoc($presult);
      $category=$parent['category'].'-'.$child['category'];
      ?>
      <tr>
        <td>
          <a href="products.php?edit=<?=$product['id'];?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-pencil"></span></a>
          <a href="products.php?delete=<?=$product['id'];?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-remove"></span></a>
        </td>
        <td><?=$product['title'];?></td>
        <td><?=money($product['price']);?></td>
        <td><?=$category;?></td>
        <td>
          <!-- Si featured es 0 lo cambia a 1 y si es 1 a 0 -->
          <a href="products.php?featured=<?=(($product['featured']==0)?'1':'0');?>&id=<?=$product['id'];?>" class="btn btn-xs btn-default">
            <span class="glyphicon glyphicon-<?=(($product['featured']==1)?'minus':'plus');?>"></span>
          </a>
          <!-- Añade texto en caso esté en 1 -->
          &nbsp <?=(($product['featured']==1)?'Featured Product':'');?>
        </td>
        <td>0</td>
      </tr>
    <?php endwhile; ?>
    <tr>

    </tr>
  </tbody>
</table>



<?php
  }
  include 'includes/footer.php';

?>
