<?php
  require_once $_SERVER['DOCUMENT_ROOT'].'/tutorial/phpProject2/core/init.php';
  if(!is_logged_in())
  {
    //Redirige a pantalla de iniciar sesión
    login_error_redirect();
  }
  include 'includes/head.php';
  include 'includes/navigation.php';
  $dbpath='';

  if(isset($_GET['add'])||isset($_GET['edit']))
  {
    $brandQuery=$db->query("SELECT * FROM brand");
    $parentQuery=$db->query("SELECT * FROM categories WHERE parent=0 ORDER BY category");
    //Editar o agregar
    $title=((isset($_POST['title'])&&$_POST['title']!='')?sanitize($_POST['title']):'');
    $brand=((isset($_POST['brand'])&&!empty($_POST['brand']))?sanitize($_POST['brand']):'');
    $parent=((isset($_POST['parent'])&&!empty($_POST['parent']))?sanitize($_POST['parent']):'');
    $category=((isset($_POST['child'])&&!empty($_POST['child']))?sanitize($_POST['child']):'');
    $price=((isset($_POST['price'])&&$_POST['price']!='')?sanitize($_POST['price']):'');
    $list_price=((isset($_POST['list_price'])&&$_POST['list_price']!='')?sanitize($_POST['list_price']):'');
    $description=((isset($_POST['description'])&&$_POST['description']!='')?sanitize($_POST['description']):'');
    $sizes=((isset($_POST['sizes'])&&$_POST['sizes']!='')?sanitize($_POST['sizes']):'');
    $saved_image='';
    if(isset($_GET['edit']))
    {
      $edit_id=(int)$_GET['edit'];
      $productresults=$db->query("SELECT * FROM products WHERE id='$edit_id'");
      $product=mysqli_fetch_assoc($productresults);
      //Variable de petición ficticia para saber que se elimina la imagen
      if(isset($_GET['delete_image']))
      {
        //Obtiene la ruta y la concatena para obtener la ruta absoluta
        $image_url=$_SERVER['DOCUMENT_ROOT'].$product['image'];
        //Elimina la imagen a la carpeta en la ruta especificada
        unlink($image_url);
        $db->query("UPDATE products SET image='' WHERE id='$edit_id'");
        header('Location: products.php?edit='.$edit_id);
      }
      $category=((isset($_POST['child'])&&$_POST['child']!='')?sanitize($_POST['child']):$product['categories']);
      //Establece el valor a ser editado cuando el campo ha sido borrado y ocurre un error
      //Nemo click editado, borra el campo en edición, ocurre error y escribe de nuevo Nemo
      //Cuando está vacio, obtiene de la BD
      //Cuando está sobreescrito, obtiene lo mismo luego de dar a Editar
      //Cuando le dio click en editar, obtiene de la BD por primera vez
      $title=((isset($_POST['title'])&&!empty($_POST['title']))?sanitize($_POST['title']):$product['title']);
      $brand=((isset($_POST['brand'])&&!empty($_POST['brand']))?sanitize($_POST['brand']):$product['brand']);
      $parentQ=$db->query("SELECT * FROM categories WHERE id='$category'");
      $parentResult=mysqli_fetch_assoc($parentQ);
      $parent=((isset($_POST['parent'])&&!empty($_POST['parent']))?sanitize($_POST['parent']):$parentResult['parent']);
      $price=((isset($_POST['price'])&&!empty($_POST['price']))?sanitize($_POST['price']):$product['price']);
      $list_price=((isset($_POST['list_price']))?sanitize($_POST['list_price']):$product['list_price']);
      $description=((isset($_POST['description']))?sanitize($_POST['description']):$product['description']);
      $sizes=((isset($_POST['sizes'])&&!empty($_POST['sizes']))?sanitize($_POST['sizes']):$product['sizes']);
      $saved_image=(($product['image']!='')?$product['image']:'');
      $dbpath=$saved_image;
    }
    //Cuando la variable de tamaños y cantidades obtenga valor o sea modificada
    //Entrará a esta condicional y realizará la división de la cadena para ser mostrada por el modal
    //Esto se realiza siempre que sizes no esté vacía
    //En primer lugar obtendrá al click en edición, los tamaños y cantidades correspondientes en la caja de texto, luego esto en el modal
    //Si eliminamos el texto, obtendrá de la BD
    //Si modificamos se guardará por realizar petición POST
    //Si deseamos agregar, inicialmente estará vacía, luego al rellenar se dará la petición y el campo obtendrá el texto de ello.
    if(!empty($sizes))
    {
      $sizeString=sanitize($sizes);
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
    if($_POST)
    {
      $temp_image=$dbpath;
      $dbpath='';
      $errors=array();
      $required=array('title','brand','price','parent','child','sizes');
      foreach ($required as $field) {
        if($_POST[$field]=='')
        {
          $errors[]='Rellenar todos los campos con asterisco.';
          break;
        }
      }
      if(!empty($_FILES['photo']['name']))
      {
        //Array del archivo, información
        $photo=$_FILES['photo'];
        //Campo nombre del archivo
        $name=$photo['name'];
        //Divide el nombre de la extensión
        $nameArray=explode('.',$name);
        //Nombre
        $fileName=$nameArray[0];
        //Extensión : jpg,png,etc.
        $fileExt=$nameArray[1];
        //Campo tipo de archivo
        $mime=explode('/',$photo['type']);
        //Tipo de archivo
        $mimeType=$mime[0];
        //Extensión del tipo de archivo
        $mimeExt=$mime[1];
        //Ubicación temporal
        $tmpLoc=$photo['tmp_name'];
        //Tamaño en bytes
        $fileSize=$photo['size'];
        //Si no subió una imagen.
        $allowed=array('png','jpg','jpeg','gif');
        //Encriptar el nombre de la imagen
        $uploadName=md5(microtime()).'.'.$fileExt;
        //Ruta para guardar la imagen
        $uploadPath=BASEURL.'/images/products/'.$uploadName;
        //Ruta para la base de datos y que será obtenida por la página principal
        $dbpath='/tutorial/phpProject2/images/products/'.$uploadName;
        if($mimeType!='image')
        {
          $errors[]='El archivo debe ser una imagen.';
        }
        //Busca la variable $fileExt en el array $allowed que contiene los tipos de extensión
        elseif(!in_array($fileExt,$allowed))
        {
          $errors[]='La imagen no coincide con el formato requerido. JPG,JPEG,PNG o GIF';
        }
        elseif($fileSize>15000000)
        {
          $errors[]='Archivo muy pesado, no supere los 15mb.';
        }/*
        elseif($fileExt!=$mimeExt&&$mimeExt=='jpeg'&&$fileExt!='jpg')
        {
          $error[]='La extensión no coincide con el archivo ingresado';
        }*/
      }
      else{
        if(empty($temp_image))
        {
          $errors[]="Debe ingresar una imagen del producto.";
        }
        else{
          $dbpath=$temp_image;
        }
      }
      if(!empty($errors))
      {
        echo display_errors($errors);
      }else{
        //agregar todo a la BD
        //Agregar imagen a la carpeta de la ruta especificada
        move_uploaded_file($tmpLoc,$uploadPath);
        $insertSql="INSERT INTO products(title,price,list_price,brand,categories,sizes,image,description) VALUES('$title','$price','$list_price','$brand','$category','$sizes','$dbpath','$description')";
        if(isset($_GET['edit']))
        {
          $insertSql="UPDATE products SET title='$title',price='$price',list_price='$list_price',brand='$brand',categories='$category',sizes='$sizes',image='$dbpath',description='$description' WHERE id='$edit_id'";
        }
        $db->query($insertSql);
        header('Location: products.php');
      }
    }
    ?>
    <h2 class="text-center"><?=((isset($_GET['edit']))?'Editar un ':'Agregar un nuevo ');?>producto</h2>
    <form class="" action="products.php?<?=((isset($_GET['edit']))?'edit='.$edit_id:'add=1');?>" method="post" enctype="multipart/form-data">
    <div class="row">
      <div class="form-group col-md-3">
        <label for="title">Título*:</label>
        <input type="text" name="title" class="form-control" id="title" value="<?=$title;?>">
      </div>
      <div class="form-group col-md-3">
        <label for="brand">Marca*:</label>
        <select class="form-control" id="brand" name="brand">
          <option value="" <?=(($brand=='')?' selected':'')?>></option>
          <?php while($b=mysqli_fetch_assoc($brandQuery)): ?>
            <option value="<?=$b['id'];?>" <?=(($brand==$b['id'])?' selected':'')?>><?=$b['brand'];?></option>
          <?php endwhile; ?>
        </select>
      </div>
      <div class="form-group col-md-3">
        <label for="parent">Categoría padre</label>
        <select class="form-control" name="parent" id="parent">
          <option value=""<?=(($parent=='')?' selected':'')?>></option>
          <?php while ($p=mysqli_fetch_assoc($parentQuery)):?>
            <option value="<?=$p['id'];?>" <?=(($parent==$p['id'])?' selected':'');?>><?=$p['category'];?></option>
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
        <input type="text" name="price" id="price" class="form-control" value="<?=$price;?>">
      </div>
      <div class="form-group col-md-3">
        <label for="list_price">Precio de lista:</label>
        <input type="text" name="list_price" id="list_price" class="form-control" value="<?=$list_price;?>">
      </div>
      <div class="form-group col-md-3">
        <label>Cantidad y tamaños*:</label>
        <button class="btn btn-default form-control" onclick="jQuery('#sizesModal').modal('toggle');return false;" name="button">Cantidad y tamaños</button>
      </div>
      <div class="form-group col-md-3">
        <label for="sizes">Vista previa: Cantidad y tamaños</label>
        <input class="form-control" type="text" name="sizes" id="sizes" value="<?=$sizes;?>" readonly>
      </div>
    </div>
    <div class="row">
      <div class="form-group col-md-6">
        <?php if($saved_image!=''): ?>
          <div class="saved-image">
            <img src="<?=$saved_image;?>" alt="<?=$saved_image;?>"><br>
            <a href="products.php?delete_image=1&edit=<?=$edit_id;?>" class="text-danger">Eliminar imagen</a>
          </div>
          <?php else: ?>
            <label for="photo">Imagen del producto:</label>
            <input type="file" name="photo" class="form-control" id="photo">
        <?php endif; ?>
      </div>
      <div class="form-group col-md-6">
        <label for="description">Descripción:</label>
        <textarea name="description" id="description" class="form-control" rows="6"><?=$description;?></textarea>
      </div>
    </div>
    <div class="form-group pull-right">
      <input type="submit" class="form-control btn btn-success pull-right" value="<?=((isset($_GET['edit']))?'Editar ':'Agregar ');?>producto">
      <hr>
      <a href="products.php" class="form-control btn btn-default">Cancelar</a>
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
            <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
            <button type="button" class="btn btn-primary" onclick="updateSizes();jQuery('#sizesModal').modal('toggle');return false;">Guardar cambios</button>
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
  if(isset($_GET['delete']))
  {
    $did=(int)$_GET['delete'];
    $db->query("UPDATE products SET deleted='1' WHERE id='$did'");
    header('Location: products.php');
  }
 ?>

<h2 class="text-center">Productos</h2>
<a href="products.php?add=1" class="btn btn-success pull-right" id="add-product-btn">Añadir producto</a>
<div class="clearfix"></div><hr>
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
          &nbsp <?=(($product['featured']==1)?'Producto destacado':'');?>
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
  include 'includes/footer.php';?>

<script type="text/javascript">
//Cuando se cargue la página se muestra la lista de categorías hijas
//Se mandará por ajax a child_categories, la categoría que debería estar seleccionada
//En caso esté vacía buscará en la BD y mandará el ID
//En caso se seleccione algo, mandará esa selección
//Si ocurre error $category almacena el valor
    jQuery('document').ready(function(){
      get_child_options('<?=$category;?>');
    });
</script>
