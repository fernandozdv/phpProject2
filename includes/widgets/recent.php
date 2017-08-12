<h3 class="text-center">Productos populares</h3>
<?php
  $transQ=$db->query("SELECT * FROM cart WHERE paid= 1 ORDER BY id DESC LIMIT 5");
  $used_ids=array();
  while($row=mysqli_fetch_assoc($transQ))
  {
    $json_items=$row['items'];
    $items=json_decode($json_items,true);
    foreach($items as $item)
    {
      if(!in_array($item['id'],$used_ids))
      {
        $used_ids[]=$item['id'];
      }
    }
  }
 ?>
 <div id="recent_widget">
   <table class="table table-condensed">
     <?php foreach($used_ids as $id):
       $productQ=$db->query("SELECT id,title FROM products WHERE id='$id'");
       $product=mysqli_fetch_assoc($productQ);
       ?>
       <tr>
         <td><?=$product['title'];?></td>
         <td><a href="#p" onclick="detailsmodal('<?=$id;?>')">Ver</a></td>
       </tr>
     <?php endforeach; ?>
   </table>
 </div>
