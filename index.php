<?php
//Principal
  require_once 'core/init.php';
  include 'includes/head.php';
  include 'includes/navigation.php';
  include 'includes/headerfull.php';
  include 'includes/leftbar.php';
//Mostrar productos destacados
  $sql="SELECT * FROM products WHERE featured=1 AND deleted='0'";
  $featured=$db->query($sql);
  ?>
      <!--main content-->
      <div class="col-md-8">
        <div class="row">
          <h2 class="text-center" id="p">Productos destacados</h2>
          <?php while($product=mysqli_fetch_assoc($featured)) {
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
