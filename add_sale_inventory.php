<?php
  $page_title = 'Add Sale';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
   page_require_level(1);
  $all_transport_stock = find_by_sql("SELECT * FROM transport_stock WHERE available_qty > 0");
?>
<?php
 if(isset($_POST['add_sale'])){
   $req_fields = array('shop_name','product_id','qty','price','sale_date');
   validate_fields($req_fields);
   if(empty($errors)){
     $shop_name = remove_junk($db->escape($_POST['shop_name']));
     $p_id      = remove_junk($db->escape($_POST['product_id']));
     $s_qty     = remove_junk($db->escape($_POST['qty']));
     $s_price   = remove_junk($db->escape($_POST['price']));
     $s_date    = remove_junk($db->escape($_POST['sale_date']));
     $total     = $s_qty * $s_price;
     
     // Get product from transport_stock
     $query = "SELECT product_id, product_name, available_qty FROM transport_stock WHERE id = '{$p_id}'";
     $result = $db->query($query);
     if($result->num_rows > 0){
       $product = $result->fetch_assoc();
       if($product['available_qty'] >= $s_qty){
         // Reduce transport stock
         $new_qty = $product['available_qty'] - $s_qty;
         $update_query = "UPDATE transport_stock SET available_qty = '{$new_qty}' WHERE id = '{$p_id}'";
         $db->query($update_query);
         
         // Add sale
         $insert_query = "INSERT INTO sales_inventory (shop_name, product_id, product_name, qty, price, total, sale_date) VALUES ('{$shop_name}', '{$product['product_id']}', '{$product['product_name']}', '{$s_qty}', '{$s_price}', '{$total}', '{$s_date}')";
         if($db->query($insert_query)){
           $session->msg('s',"Sale added ");
           redirect('add_sale_inventory.php', false);
         } else {
           $session->msg('d',' Sorry failed to add sale!');
           redirect('add_sale_inventory.php', false);
         }
       } else {
         $session->msg('d',' Insufficient quantity in transport stock!');
         redirect('add_sale_inventory.php', false);
       }
     } else {
       $session->msg('d',' Product not found in transport stock!');
       redirect('add_sale_inventory.php', false);
     }

   } else{
     $session->msg("d", $errors);
     redirect('add_sale_inventory.php',false);
   }

 }

?>
<?php include_once('layouts/header.php'); ?>
<div class="row">
  <div class="col-md-12">
    <?php echo display_msg($msg); ?>
  </div>
</div>
  <div class="row">
  <div class="col-md-8">
      <div class="panel panel-default">
        <div class="panel-heading">
          <strong>
            <span class="glyphicon glyphicon-shopping-cart"></span>
            <span>Add Sale</span>
         </strong>
        </div>
        <div class="panel-body">
         <div class="col-md-12">
          <form method="post" action="add_sale_inventory.php" class="clearfix">
              <div class="form-group">
                <div class="input-group">
                  <span class="input-group-addon">
                   <i class="glyphicon glyphicon-home"></i>
                  </span>
                  <input type="text" class="form-control" name="shop_name" placeholder="Shop Name" required>
               </div>
              </div>
              <div class="form-group">
                <select class="form-control" name="product_id" required>
                  <option value="">Select Product from Transport Stock</option>
                  <?php 
                  $selected_product = isset($_GET['product_id']) ? (int)$_GET['product_id'] : 0;
                  foreach ($all_transport_stock as $product): 
                  ?>
                    <option value="<?php echo (int)$product['id']; ?>" <?php if($product['id'] == $selected_product) echo 'selected'; ?>>
                      <?php echo $product['product_name'] . ' (Available: ' . $product['available_qty'] . ')'; ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div class="form-group">
               <div class="row">
                 <div class="col-md-4">
                   <div class="input-group">
                     <span class="input-group-addon">
                      <i class="glyphicon glyphicon-shopping-cart"></i>
                     </span>
                     <input type="number" class="form-control" name="qty" placeholder="Quantity" min="1" required>
                   </div>
                 </div>
                 <div class="col-md-4">
                   <div class="input-group">
                     <span class="input-group-addon">
                      <i class="glyphicon glyphicon-usd"></i>
                     </span>
                     <input type="number" step="0.01" class="form-control" name="price" placeholder="Price" min="0" required>
                   </div>
                 </div>
                 <div class="col-md-4">
                   <div class="input-group">
                     <span class="input-group-addon">
                      <i class="glyphicon glyphicon-calendar"></i>
                     </span>
                     <input type="date" class="form-control" name="sale_date" required>
                   </div>
                 </div>
               </div>
              </div>
              <button type="submit" name="add_sale" class="btn btn-success">Add Sale</button>
          </form>
         </div>
        </div>
      </div>
    </div>
  </div>
<?php include_once('layouts/footer.php'); ?>