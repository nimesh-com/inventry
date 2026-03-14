<?php
  $page_title = 'Admin Home Page';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
   page_require_level(1);
?>
<?php
 $c_categorie     = count_by_id('categories');
 $c_product       = count_by_id('products');
 $c_sale          = count_by_id('sales');
 $c_user          = count_by_id('users');
 $c_main_stock    = count_by_id('main_stock');
 $c_transport_stock = count_by_id('transport_stock');
 $c_inventory_sales = count_by_id('sales_inventory');
 $products_sold   = find_higest_saleing_product('10');
 $recent_products = find_recent_product_added('5');
 $recent_sales    = find_recent_inventory_sale_added('5')
?>
<?php include_once('layouts/header.php'); ?>

<div class="row">
   <div class="col-md-6">
     <?php echo display_msg($msg); ?>
   </div>
</div>
  <div class="row">
    <a href="users.php" style="color:black;">
		<div class="col-md-3">
       <div class="panel panel-box clearfix">
         <div class="panel-icon pull-left bg-secondary1">
          <i class="glyphicon glyphicon-user"></i>
        </div>
        <div class="panel-value pull-right">
          <h2 class="margin-top"> <?php  echo $c_user['total']; ?> </h2>
          <p class="text-muted">Users</p>
        </div>
       </div>
    </div>
	</a>
	
	<a href="categorie.php" style="color:black;">
    <div class="col-md-3">
       <div class="panel panel-box clearfix">
         <div class="panel-icon pull-left bg-red">
          <i class="glyphicon glyphicon-th-large"></i>
        </div>
        <div class="panel-value pull-right">
          <h2 class="margin-top"> <?php  echo $c_categorie['total']; ?> </h2>
          <p class="text-muted">Categories</p>
        </div>
       </div>
    </div>
	</a>
	
	<a href="main_stock_list.php" style="color:black;">
    <div class="col-md-3">
       <div class="panel panel-box clearfix">
         <div class="panel-icon pull-left bg-blue2">
          <i class="glyphicon glyphicon-briefcase"></i>
        </div>
        <div class="panel-value pull-right">
          <h2 class="margin-top"> <?php  echo $c_main_stock['total']; ?> </h2>
          <p class="text-muted">Main Stock</p>
        </div>
       </div>
    </div>
	</a>
	
	<a href="sales_report_inventory.php" style="color:black;">
    <div class="col-md-3">
       <div class="panel panel-box clearfix">
         <div class="panel-icon pull-left bg-green">
          <i class="glyphicon glyphicon-usd"></i>
        </div>
        <div class="panel-value pull-right">
          <h2 class="margin-top"> <?php  echo $c_inventory_sales['total']; ?></h2>
          <p class="text-muted">Inventory Sales</p>
        </div>
       </div>
    </div>
	</a>
</div>
  
  <div class="row">
   <div class="col-md-4">
     <div class="panel panel-default">
       <div class="panel-heading">
         <strong>
           <span class="glyphicon glyphicon-road"></span>
           <span>Transport Stock Summary</span>
         </strong>
       </div>
       <div class="panel-body">
         <table class="table table-striped table-bordered table-condensed">
          <thead>
           <tr>
             <th>Product</th>
             <th>Available</th>
           <tr>
          </thead>
          <tbody>
            <?php
            $transport_items = find_by_sql("SELECT product_name, available_qty FROM transport_stock ORDER BY available_qty DESC LIMIT 5");
            foreach ($transport_items as $item): 
            ?>
              <tr>
                <td><?php echo remove_junk($item['product_name']); ?></td>
                <td><?php echo (int)$item['available_qty']; ?></td>
              </tr>
            <?php endforeach; ?>
          <tbody>
         </table>
       </div>
     </div>
   </div>
   <div class="col-md-4">
      <div class="panel panel-default">
        <div class="panel-heading">
          <strong>
            <span class="glyphicon glyphicon-usd"></span>
            <span>Latest Inventory Sales</span>
          </strong>
        </div>
        <div class="panel-body">
          <table class="table table-striped table-bordered table-condensed">
       <thead>
         <tr>
           <th class="text-center" style="width: 50px;">#</th>
           <th>Product Name</th>
           <th>Shop</th>
           <th>Total Sale</th>
         </tr>
       </thead>
       <tbody>
         <?php foreach ($recent_sales as  $recent_sale): ?>
         <tr>
           <td class="text-center"><?php echo count_id();?></td>
           <td><?php echo remove_junk(first_character($recent_sale['name'])); ?></td>
           <td><?php echo remove_junk($recent_sale['shop_name']); ?></td>
           <td>$<?php echo remove_junk($recent_sale['price'] * $recent_sale['qty']); ?></td>
        </tr>

       <?php endforeach; ?>
       </tbody>
     </table>
    </div>
   </div>
  </div>
  <div class="col-md-4">
    <div class="panel panel-default">
      <div class="panel-heading">
        <strong>
          <span class="glyphicon glyphicon-briefcase"></span>
          <span>Manage Stock</span>
        </strong>
      </div>
      <div class="panel-body">
        <div class="list-group">
          <a class="list-group-item" href="add_main_stock.php">
            <i class="glyphicon glyphicon-plus"></i> Add Main Stock
          </a>
          <a class="list-group-item" href="main_stock_list.php">
            <i class="glyphicon glyphicon-list"></i> Main Stock List
          </a>
          <a class="list-group-item" href="transfer_to_transport.php">
            <i class="glyphicon glyphicon-transfer"></i> Transfer to Transport
          </a>
          <a class="list-group-item" href="transport_stock_list.php">
            <i class="glyphicon glyphicon-road"></i> Transport Stock List
          </a>
          <a class="list-group-item" href="restock_transport.php">
            <i class="glyphicon glyphicon-refresh"></i> Restock Transport
          </a>
          <a class="list-group-item" href="add_sale_inventory.php">
            <i class="glyphicon glyphicon-shopping-cart"></i> Add Sale
          </a>
          <a class="list-group-item" href="sales_report_inventory.php">
            <i class="glyphicon glyphicon-stats"></i> Sales Report
          </a>
        </div>
      </div>
    </div>
  </div>
</div>
 </div>
  <div class="row">

  </div>



<?php include_once('layouts/footer.php'); ?>
