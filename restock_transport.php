<?php
  $page_title = 'Restock Transport Stock';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
   page_require_level(2);
  $all_transport_stock = find_all('transport_stock');
?>
<?php
 if(isset($_POST['restock'])){
   $req_fields = array('product_id','qty','restock_date');
   validate_fields($req_fields);
   if(empty($errors)){
     $p_id  = remove_junk($db->escape($_POST['product_id']));
     $r_qty = remove_junk($db->escape($_POST['qty']));
     $r_date = remove_junk($db->escape($_POST['restock_date']));
     $note  = remove_junk($db->escape($_POST['note']));
     
     // Update transport stock
     $query = "UPDATE transport_stock SET available_qty = available_qty + '{$r_qty}' WHERE id = '{$p_id}'";
     if($db->query($query)){
       $session->msg('s',"Transport stock restocked ");
       redirect('restock_transport.php', false);
     } else {
       $session->msg('d',' Sorry failed to restock!');
       redirect('restock_transport.php', false);
     }

   } else{
     $session->msg("d", $errors);
     redirect('restock_transport.php',false);
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
            <span class="glyphicon glyphicon-plus"></span>
            <span>Restock Transport Stock</span>
         </strong>
        </div>
        <div class="panel-body">
         <div class="col-md-12">
          <form method="post" action="restock_transport.php" class="clearfix">
              <div class="form-group">
                <select class="form-control" name="product_id" required>
                  <option value="">Select Product from Transport Stock</option>
                  <?php foreach ($all_transport_stock as $product): ?>
                    <option value="<?php echo (int)$product['id']; ?>">
                      <?php echo $product['product_name'] . ' (Current Available: ' . $product['available_qty'] . ')'; ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div class="form-group">
               <div class="row">
                 <div class="col-md-6">
                   <div class="input-group">
                     <span class="input-group-addon">
                      <i class="glyphicon glyphicon-shopping-cart"></i>
                     </span>
                     <input type="number" class="form-control" name="qty" placeholder="Restock Quantity" min="1" required>
                   </div>
                 </div>
                 <div class="col-md-6">
                   <div class="input-group">
                     <span class="input-group-addon">
                      <i class="glyphicon glyphicon-calendar"></i>
                     </span>
                     <input type="date" class="form-control" name="restock_date" required>
                   </div>
                 </div>
               </div>
              </div>
              <div class="form-group">
                <textarea class="form-control" name="note" placeholder="Note (optional)"></textarea>
              </div>
              <button type="submit" name="restock" class="btn btn-warning">Restock</button>
          </form>
         </div>
        </div>
      </div>
    </div>
  </div>
<?php include_once('layouts/footer.php'); ?>