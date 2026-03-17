<?php
  $page_title = 'Add Main Stock';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
   page_require_level(1);
  $all_main_stock = find_all('main_stock');
?>
<?php
 if(isset($_POST['add_main_stock'])){
   if(isset($_POST['main_stock_select'])){
     $req_fields = array('main_stock_select','quantity','price');
   } else {
     $req_fields = array('product_name','product_code','quantity','price');
   }
   validate_fields($req_fields);
   if(empty($errors)){
     if(isset($_POST['main_stock_select'])){
       $id = (int)$_POST['main_stock_select'];
       $p_qty   = remove_junk($db->escape($_POST['quantity']));
       $p_price = remove_junk($db->escape($_POST['price']));
       $date    = make_date();
       $query = "UPDATE main_stock SET quantity = quantity + {$p_qty}, price = '{$p_price}', created_date = '{$date}' WHERE id = {$id}";
       $msg = "Main stock updated ";
     } else {
       $p_name  = remove_junk($db->escape($_POST['product_name']));
       $p_code  = remove_junk($db->escape($_POST['product_code']));
       $p_qty   = remove_junk($db->escape($_POST['quantity']));
       $p_price = remove_junk($db->escape($_POST['price']));
       $date    = make_date();
       $query  = "INSERT INTO main_stock (";
       $query .=" product_name,product_code,quantity,price,created_date";
       $query .=") VALUES (";
       $query .=" '{$p_name}', '{$p_code}', '{$p_qty}', '{$p_price}', '{$date}'";
       $query .=")";
       $msg = "Product added to main stock ";
     }
     if($db->query($query)){
       $session->msg('s', $msg);
       redirect('add_main_stock.php', false);
     } else {
       $session->msg('d',' Sorry failed!');
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
          <ul class="nav nav-tabs">
            <li class="active"><a data-toggle="tab" href="#add_new">Add New Stock</a></li>
            <?php if(count($all_main_stock) > 0): ?>
            <li><a data-toggle="tab" href="#update_existing">Update Existing Stock</a></li>
            <?php endif; ?>
          </ul>
          <div class="tab-content">
            <div id="add_new" class="tab-pane fade in active">
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
                <button type="submit" name="add_main_stock" class="btn btn-danger">Add Stock</button>
              </form>
            </div>
            <?php if(count($all_main_stock) > 0): ?>
            <div id="update_existing" class="tab-pane fade">
              <form method="post" action="add_main_stock.php" class="clearfix">
                <div class="form-group">
                  <div class="input-group">
                    <span class="input-group-addon">
                     <i class="glyphicon glyphicon-th-large"></i>
                    </span>
                    <select class="form-control" name="main_stock_select" required>
                      <option value="">Select Main Stock Item</option>
                      <?php foreach ($all_main_stock as $stock): ?>
                        <option value="<?php echo $stock['id']; ?>"><?php echo $stock['product_name']; ?> (Code: <?php echo $stock['product_code']; ?>, Qty: <?php echo $stock['quantity']; ?>, Price: <?php echo $stock['price']; ?>)</option>
                      <?php endforeach; ?>
                    </select>
                 </div>
                </div>
                <div class="form-group">
                 <div class="row">
                   <div class="col-md-6">
                     <div class="input-group">
                       <span class="input-group-addon">
                        <i class="glyphicon glyphicon-shopping-cart"></i>
                       </span>
                       <input type="number" class="form-control" name="quantity" placeholder="Quantity to Add" min="1" required>
                     </div>
                   </div>
                   <div class="col-md-6">
                     <div class="input-group">
                       <span class="input-group-addon">
                        <i class="glyphicon glyphicon-usd"></i>
                       </span>
                       <input type="number" step="0.01" class="form-control" name="price" placeholder="New Price" min="0" required>
                     </div>
                   </div>
                 </div>
                </div>
                <button type="submit" name="add_main_stock" class="btn btn-danger">Update Stock</button>
              </form>
            </div>
            <?php endif; ?>
          </div>
         </div>
  </div>
<?php include_once('layouts/footer.php'); ?>