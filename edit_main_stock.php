<?php
  $page_title = 'Edit Main Stock';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
   page_require_level(1);
?>
<?php
$main_stock = find_by_id('main_stock',(int)$_GET['id']);
if(!$main_stock){
  $session->msg("d","Missing main stock id.");
  redirect('main_stock_list.php');
}
?>
<?php
 if(isset($_POST['main_stock'])){
    $req_fields = array('product_name','product_code','quantity','price');
    validate_fields($req_fields);

   if(empty($errors)){
       $p_name  = remove_junk($db->escape($_POST['product_name']));
       $p_code  = remove_junk($db->escape($_POST['product_code']));
       $p_qty   = remove_junk($db->escape($_POST['quantity']));
       $p_price = remove_junk($db->escape($_POST['price']));
       $query   = "UPDATE main_stock SET";
       $query  .=" product_name ='{$p_name}', product_code ='{$p_code}', quantity ='{$p_qty}', price ='{$p_price}'";
       $query  .=" WHERE id ='{$main_stock['id']}'";
       $result = $db->query($query);
               if($result && $db->affected_rows() === 1){
                 $session->msg('s',"Main stock updated ");
                 redirect('main_stock_list.php', false);
               } else {
                 $session->msg('d',' Sorry failed to updated!');
                 redirect('edit_main_stock.php?id='.$main_stock['id'], false);
               }

   } else{
       $session->msg("d", $errors);
       redirect('edit_main_stock.php?id='.$main_stock['id'], false);
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
      <div class="panel panel-default">
        <div class="panel-heading">
          <strong>
            <span class="glyphicon glyphicon-th"></span>
            <span>Edit Main Stock</span>
         </strong>
        </div>
        <div class="panel-body">
         <div class="col-md-7">
           <form method="post" action="edit_main_stock.php?id=<?php echo (int)$main_stock['id'] ?>">
              <div class="form-group">
                <div class="input-group">
                  <span class="input-group-addon">
                   <i class="glyphicon glyphicon-th-large"></i>
                  </span>
                  <input type="text" class="form-control" name="product_name" value="<?php echo remove_junk($main_stock['product_name']);?>" required>
               </div>
              </div>
              <div class="form-group">
                <div class="input-group">
                  <span class="input-group-addon">
                   <i class="glyphicon glyphicon-barcode"></i>
                  </span>
                  <input type="text" class="form-control" name="product_code" value="<?php echo remove_junk($main_stock['product_code']);?>" required>
               </div>
              </div>
              <div class="form-group">
               <div class="row">
                 <div class="col-md-6">
                   <div class="input-group">
                     <span class="input-group-addon">
                      <i class="glyphicon glyphicon-shopping-cart"></i>
                     </span>
                     <input type="number" class="form-control" name="quantity" value="<?php echo remove_junk($main_stock['quantity']); ?>" min="0" required>
                   </div>
                 </div>
                 <div class="col-md-6">
                   <div class="input-group">
                     <span class="input-group-addon">
                      <i class="glyphicon glyphicon-usd"></i>
                     </span>
                     <input type="number" step="0.01" class="form-control" name="price" value="<?php echo remove_junk($main_stock['price']); ?>" min="0" required>
                   </div>
                 </div>
               </div>
              </div>
              <button type="submit" name="main_stock" class="btn btn-danger">Update</button>
          </form>
         </div>
        </div>
      </div>
  </div>

<?php include_once('layouts/footer.php'); ?>