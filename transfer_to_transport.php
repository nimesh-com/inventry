<?php
  $page_title = 'Transfer to Transport Stock';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
   page_require_level(2);
  $all_main_stock = find_all('main_stock');
?>
<?php
 if(isset($_POST['transfer'])){
   $req_fields = array('product_id','transfer_qty');
   validate_fields($req_fields);
   if(empty($errors)){
     $p_id  = remove_junk($db->escape($_POST['product_id']));
     $t_qty = remove_junk($db->escape($_POST['transfer_qty']));
     
     // Get current quantity from main_stock
     $query = "SELECT quantity, product_name FROM main_stock WHERE id = '{$p_id}'";
     $result = $db->query($query);
     if($result->num_rows > 0){
       $product = $result->fetch_assoc();
       if($product['quantity'] >= $t_qty){
         // Reduce main stock
         $new_qty = $product['quantity'] - $t_qty;
         $update_query = "UPDATE main_stock SET quantity = '{$new_qty}' WHERE id = '{$p_id}'";
         $db->query($update_query);
         
         // Check if product already in transport_stock
         $check_query = "SELECT id FROM transport_stock WHERE product_id = '{$p_id}'";
         $check_result = $db->query($check_query);
         $date = make_date();
         if($check_result->num_rows > 0){
           // Update existing
           $update_transport = "UPDATE transport_stock SET transfer_qty = transfer_qty + '{$t_qty}', available_qty = available_qty + '{$t_qty}' WHERE product_id = '{$p_id}'";
           $db->query($update_transport);
         } else {
           // Insert new
           $insert_query = "INSERT INTO transport_stock (product_id, product_name, transfer_qty, available_qty, transfer_date) VALUES ('{$p_id}', '{$product['product_name']}', '{$t_qty}', '{$t_qty}', '{$date}')";
           $db->query($insert_query);
         }
         $session->msg('s',"Transferred to transport stock ");
         redirect('transfer_to_transport.php', false);
       } else {
         $session->msg('d',' Insufficient quantity in main stock!');
         redirect('transfer_to_transport.php', false);
       }
     } else {
       $session->msg('d',' Product not found!');
       redirect('transfer_to_transport.php', false);
     }

   } else{
     $session->msg("d", $errors);
     redirect('transfer_to_transport.php',false);
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
            <span class="glyphicon glyphicon-transfer"></span>
            <span>Transfer to Transport Stock</span>
         </strong>
        </div>
        <div class="panel-body">
         <div class="col-md-12">
          <form method="post" action="transfer_to_transport.php" class="clearfix">
              <div class="form-group">
                <select class="form-control" name="product_id" required>
                  <option value="">Select Product from Main Stock</option>
                  <?php 
                  $selected_product = isset($_GET['product_id']) ? (int)$_GET['product_id'] : 0;
                  foreach ($all_main_stock as $product): 
                  ?>
                    <option value="<?php echo (int)$product['id']; ?>" <?php if($product['id'] == $selected_product) echo 'selected'; ?>>
                      <?php echo $product['product_name'] . ' (Available: ' . $product['quantity'] . ')'; ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div class="form-group">
                <div class="input-group">
                  <span class="input-group-addon">
                   <i class="glyphicon glyphicon-shopping-cart"></i>
                  </span>
                  <input type="number" class="form-control" name="transfer_qty" placeholder="Transfer Quantity" min="1" required>
               </div>
              </div>
              <button type="submit" name="transfer" class="btn btn-primary">Transfer</button>
          </form>
         </div>
        </div>
      </div>
    </div>
  </div>
<?php include_once('layouts/footer.php'); ?>