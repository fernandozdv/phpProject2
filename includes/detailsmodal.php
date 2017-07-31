<?php
//buscar el producto solicitado para mostrar su modal
  require_once '../core/init.php';
  $id= $_POST['id'];
  $id= (int)$id;
  $sql="SELECT * FROM products WHERE id='$id'";
  $result=$db->query($sql);
  $product=mysqli_fetch_assoc($result);
  $brand_id=$product['brand'];
  $sql="SELECT brand FROM brand WHERE id='$brand_id'";
  $brand_query=$db->query($sql);
  $brand=mysqli_fetch_assoc($brand_query);
  //Tamaño de las prendas disponibles
  $sizestring=$product['sizes'];
  //Separa la cadena en elementos separados por ',' en este caso y los almacena en un array
  $size_array=explode(',',$sizestring);
 ?>
<!-- Details modal-->
<!-- Inicializa Buffer -->
<?php ob_start() ?>
<!--data kayboard para no dar click fuera del modal -->
<div data-backdrop = "static" data-keyboard = "false" class="modal fade details-1" id="details-modal" tabindex="-1" role="dialog" aria-labelledby="details-1" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <!-- X llama a cerrar el modal -->
          <span aria-hidden="true" onclick="closeModal()">&times;</span>
        </button>
        <h4 class="modal-title text-center"><?=$product['title']?></h4>
      </div>
      <div class="modal-body">
        <div class="container-fluid">
          <div class="row">
            <div class="col-sm-6">
              <div class="center-block">
                <img src="<?=$product['image']?>" alt="<?=$product['title']?>" class="details img-responsive">
              </div>
            </div>
            <div class="col-sm-6">
              <h4>Details</h4>
              <p><?=$product['description']?></p>
              <hr>
              <p>Price: $<?=$product['price']?></p>
              <p>Brand: <?=$brand['brand']?></p>
              <form action="add_cart.php" method="post">
                <div class="form-group">
                  <div>
                    <label for="quantity">Quantity:</label>
                    <input type="text" class="form-control" id="quantity" name="quantity">
                  </div>
                  <div>
                    <p>Available: 3</p>
                  </div>

                </div>
                <div class="form-group">
                  <label for="size">Size: </label>
                  <select name="size" id="size" class="form-control">
                    <option value=""></option>
                    <?php foreach($size_array as $string)
                    {
                      //Itera el array separa nuevamente, pero ahora por ':'
                      $string_array=explode(':',$string);
                      //El indice 0 contiene el tamaño de la prenda
                      $size=$string_array[0];
                      //El indice 1 contiene la cantidad en stock de esa talla
                      $quantity=$string_array[1];
                      echo '<option value="'.$size.'">'.$size.' ('.$quantity.' Available)</option>';
                    }
                    ?>

                  </select>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <!-- El botón de cerrar también cierra el modal -->
        <button class="btn btn-default"  onclick="closeModal()">Close</button>
        <button class="btn btn-warning" type="submit"><span class="glyphicon glyphicon-shopping-cart"></span>Add To Cart</button>
      </div>
    </div>
  </div>
</div>
<script>
  function closeModal(){
    //Apaga el modal
    jQuery('#details-modal').modal('hide');
    setTimeout(function(){
      //Remueve el fondo y el modal en medio segundo
      jQuery('#details-modal').remove();
      jQuery('.modal-backdrop').remove();
    },500);
  }
</script>
<!-- Cierra el buffer y libera memoria, debe hacer echo para mostrar el contenido -->
<?php echo ob_get_clean(); ?>
