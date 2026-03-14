<?php
  $page_title = 'Add Main Stock';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
   page_require_level(1);
?>
<?php
 if(isset($_POST['add_main_stock'])){
   $req_fields = array('product_name','product_code','quantity','price');
   validate_fields($req_fields);
   if(empty($errors)){
     $p_name  = remove_junk($db->escape($_POST['product_name']));
     $p_code  = remove_junk($db->escape($_POST['product_code']));
     $p_qty   = remove_junk($db->escape($_POST['quantity']));
     $p_price = remove_junk($db->escape($_POST['price']));
     $date    = make_date();
     // Insert into main_stock
     $query  = "INSERT INTO main_stock (";
     $query .=" product_name,product_code,quantity,price,created_date";
     $query .=") VALUES (";
     $query .=" '{$p_name}', '{$p_code}', '{$p_qty}', '{$p_price}', '{$date}'";
     $query .=")";
     if($db->query($query)){
       $session->msg('s',"Product added to main stock ");
       redirect('add_main_stock.php', false);
     } else {
       $session->msg('d',' Sorry failed to add!');
       redirect('add_main_stock.php', false);
     }

   } else{
     $session->msg("d", $errors);
     redirect('add_main_stock.php',false);
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
            <span class="glyphicon glyphicon-th"></span>
            <span>Add Product to Main Stock</span>
         </strong>
        </div>
        <div class="panel-body">
         <div class="col-md-12">
          <form method="post" action="add_main_stock.php" class="clearfix">
              <div class="form-group">
                <div class="input-group">
                  <span class="input-group-addon">
                   <i class="glyphicon glyphicon-th-large"></i>
                  </span>
                  <input type="text" class="form-control" name="product_name" placeholder="Product Name" required>
               </div>
              </div>
              <div class="form-group">
                <div class="input-group">
                  <span class="input-group-addon">
                   <i class="glyphicon glyphicon-barcode"></i>
                  </span>
                  <input type="text" class="form-control" name="product_code" placeholder="Product Code" required>
               </div>
              </div>
              <div class="form-group">
               <div class="row">
                 <div class="col-md-6">
                   <div class="input-group">
                     <span class="input-group-addon">
                      <i class="glyphicon glyphicon-shopping-cart"></i>
                     </span>
                     <input type="number" class="form-control" name="quantity" placeholder="Quantity" min="1" required>
                   </div>
                 </div>
                 <div class="col-md-6">
                   <div class="input-group">
                     <span class="input-group-addon">
                      <i class="glyphicon glyphicon-usd"></i>
                     </span>
                     <input type="number" step="0.01" class="form-control" name="price" placeholder="Price" min="0" required>
                   </div>
                 </div>
               </div>
              </div>
              <button type="submit" name="add_main_stock" class="btn btn-danger">Add Product</button>
          </form>
         </div>
        </div>
      </div>
    </div>
  </div>
<?php include_once('layouts/footer.php'); ?>