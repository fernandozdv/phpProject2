<?php
//Principal
  require_once 'core/init.php';
  include 'includes/head.php';
  include 'includes/navigation.php';
  include 'includes/headerpartial.php';
  include 'includes/leftbar.php';
  //Mostrar productos por categoría
  if(isset($_GET['cat']))
  {
    $cat_id=sanitize($_GET['cat']);
  }else{
    $cat_id='';
  }
  $sql="SELECT * FROM products WHERE categories='$cat_id' AND deleted='0'";
  $productQ=$db->query($sql);
  $category=get_category($cat_id);
  ?>
      <!--main content-->
      <div class="col-md-8">
        <div class="row">
          <h2 class="text-center"><?=$category['parent'].' '.$category['child'];?></h2><hr>
          <?php while($product=mysqli_fetch_assoc($productQ)) {
            //var_dump($product)
            ?>
          <div class="col-md-3">
            <h4><?= $product['title']; ?></h4>
            <!--Imagen del producto, alt título-->
            <img src="<?= $product['image']; ?>" alt="<?= $product['title']; ?>" class="img-thumb">
            <p class="list-price text-danger">Precio anterior: <s>$<?= $product['list_price']; ?></s></p>
            <p class="price">Precio actual: $<?= $product['price']; ?></p>
            <!--Al presionar el botón de detalles, llamará a la función JS detailsmodal pasando el id del producto-->
            <button type="button" class="btn btn-sm btn-success" onclick="detailsmodal(<?= $product['id']; ?>)">Detalles</button>
          </div>
          <?php  } ?>
        </div>
      </div>
    <?php
      include 'includes/rightbar.php';
      include 'includes/footer.php';
    ?>
