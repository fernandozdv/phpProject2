<?php
  require_once $_SERVER['DOCUMENT_ROOT'].'/tutorial/phpProject2/core/init.php';
  include 'includes/head.php';
  include 'includes/navigation.php';

  $sql="SELECT * FROM categories WHERE parent=0";
  $result=$db->query($sql);
  $errors=array();
  $category='';
  $post_parent='';
  //Editar categoría
  if(isset($_GET['edit'])&&!empty($_GET['edit']))
  {
    $edit_id=(int)$_GET['edit'];
    $edit_id=sanitize($_GET['edit']);
    $edit_sql="SELECT * FROM categories WHERE id='$edit_id'";
    $edit_result=$db->query($edit_sql);
    $edit_category=mysqli_fetch_assoc($edit_result);
  }
  //Eliminar categoría
  if(isset($_GET['delete'])&&!empty($_GET['delete']))
  {
    $delete_id=(int)$_GET['delete'];
    $delete_id=sanitize($delete_id);
    $sql="SELECT FROM categories WHERE id='$delete_id'";
    $result=$db->query($sql);
    $category=mysqli_fetch_assoc($result);
    //Si es una categoría padre
    if($category['parent']==0)
    {
      $sql="DELETE FROM categories WHERE parent='$delete_id'";
      $db->query($sql);
    }
    $dsql="DELETE FROM categories WHERE id='$delete_id'";
    $db->query($dsql);
    header('Location: categories.php');
  }

  //Cuando presione añadir categoría
  //Si existe y no es null, AND, no es vacio
  if(isset($_POST['add_category'])&&!empty($_POST['add_category']))
  {
    $post_parent=sanitize($_POST['parent']);
    $category=sanitize($_POST['category']);
    //Busca categoría ingresada
    $sqlform="SELECT * FROM categories WHERE category='$category' AND parent='$post_parent'";
    //Busca resultados cuando otra prenda desea ser editada con otra existente en su categoria padre
    if(isset($_GET['edit']))
    {
      $id=$edit_category['id'];
      $sqlform="SELECT * FROM categories WHERE category='$category' AND parent='$post_parent' AND id!='$id'";
    }
    $fresult=$db->query($sqlform);
    $count=mysqli_num_rows($fresult);
    if($category=='')
    {
      $errors[].='Categoría en blanco.';
    }
    if($count>0)
    {
      $errors[].='Categoría repetida.';
    }
    if(!empty($errors))
     {
       $display=display_errors($errors);?>
       <script type="text/javascript">
       //Espera a que se cargue DOM para recién mostrar el mensaje
         jQuery('document').ready(function(){
           jQuery('#errors').html('<?=$display;?>');
         })
       </script>
     <?php
     }else{
       $updatesql="INSERT INTO categories(category,parent) VALUES('$category','$post_parent')";
       if(isset($_GET['edit']))
       {
         $updatesql="UPDATE categories SET category='$category',parent='$post_parent' WHERE id='$edit_id'";
       }
       $db->query($updatesql);
       header("Location: categories.php");
     }

   }
   $category_value='';
   $parent_value=0;
   if(isset($_GET['edit']))
   {
     //Al editar rellenar campos con categoría seleccionada y padre al que pertenece
     $category_value=$edit_category['category'];
     $parent_value=$edit_category['parent'];
   }else{
     if(isset($_POST))
     {
       //Al ingresar y obtener error,rellenar campos con categoría seleccionada y padre al que pertenece
       $category_value=$category;
       $parent_value=$post_parent;
     }
   }
 ?>
 <h2 class="text-center">Categories</h2><hr>
 <div class="row">
   <!--Form-->
   <div class="col-md-6">
     <form class="form" action="categories.php<?=((isset($_GET['edit'])) ? '?edit='.$edit_id: '');?>" method="post">
       <legend><?=(isset($_GET['edit'])?'Editar ':'Agregar ');?>categoría</legend>
       <div id="errors">
       </div>
       <div class="form-group">
         <label for="parent">Parent</label>
         <select class="form-control" name="parent" id="parent">
           <!-- seleccionar el campo PADRE 0 en caso de error o edición -->
           <?php if(!isset($_GET['edit'])){ ?>
           <option value="0" <?=(($parent_value==0)?'selected':'');?>>Parent</option>
           <!-- Categorias en select -->
           <?php while ($parent=mysqli_fetch_assoc($result)) {?>
             <!-- seleccionar el campo en caso de error o edición -->
             <option value="<?=$parent['id'];?>" <?=(($parent_value==$parent['id'])?'selected':'');?>><?=$parent['category'];?></option>
           <?php } }else{
              $parent_name='Parent';
              while($parent=mysqli_fetch_assoc($result)){
                if($parent['id']==$parent_value)
                {
                  $parent_name=$parent['category'];
                }
              };
             ?>
             <option value="<?=$parent_value?>"><?=$parent_name;?></option>
          <?php  }?>
         </select>
       </div>
       <div class="form-group">
         <label for="category">Category</label>
         <input type="text" class="form-control" name="category" id="category" value="<?=$category_value?>">
       </div>
       <div class="form-group text-center">
         <input type="submit" name="add_category" class="btn btn-success" value="<?=((isset($_GET['edit'])?'Editar':'Agregar'));?>">
       <?php if(isset($_GET['edit'])){ ?>
         <a href="categories.php" class="btn btn-default">Cancelar</a>
       <?php } ?>
      </div>

     </form>
   </div>
   <!--Category table-->
   <div class="col-md-6">
     <table class="table table-bordered">
       <thead>
         <th>Category</th><th>Parent</th><th></th>
       </thead>
       <tbody>
         <?php
         //Busca categorias padre e itera, sobre sus hijas
         $sql="SELECT * FROM categories WHERE parent=0";
         $result=$db->query($sql);
         while($parent=mysqli_fetch_assoc($result)){
           $parent_id=(int)$parent['id'];
           $sql2="SELECT * FROM categories WHERE parent='$parent_id'";
           $cresult=$db->query($sql2);
           ?>
         <tr class="bg-primary">
           <td><?=$parent['category'];?></td>
           <td>Parent</td>
           <td>
             <a href="categories.php?edit=<?=$parent['id'];?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-pencil"></span></a>
             <a href="categories.php?delete=<?=$parent['id'];?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-remove"></span></a>
           </td>
         </tr>
            <?php while ($child=mysqli_fetch_assoc($cresult))
            {?>
              <tr class="bg-info">
                <td><?=$child['category'];?></td>
                <td><?=$parent['category'];?></td>
                <td>
                  <a href="categories.php?edit=<?=$child['id'];?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-pencil"></span></a>
                  <a href="categories.php?delete=<?=$child['id'];?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-remove"></span></a>
                </td>
              </tr>
            <?php
            }
          }
        ?>
       </tbody>
     </table>
   </div>
 </div>
<?php
  include 'includes/footer.php';
 ?>
