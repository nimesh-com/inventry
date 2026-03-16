<?php
  $page_title = 'Main Stock List';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
   page_require_level(1);
?>
<?php include_once('layouts/header.php'); ?>
<div class="row">
  <div class="col-md-12">
    <?php echo display_msg($msg); ?>
  </div>
</div>
  <div class="row">
    <div class="col-md-12">
      <div class="panel panel-default">
        <div class="panel-heading">
          <strong>
            <span class="glyphicon glyphicon-th"></span>
            <span>Main Stock List</span>
         </strong>
        </div>
        <div class="panel-body">
          <div class="row">
            <div class="col-md-12">
              <div class="btn-group">
                <a href="add_main_stock.php" class="btn btn-success">Add Main Stock</a>
                <a href="transfer_to_transport.php" class="btn btn-primary">Transfer to Transport</a>
                <a href="transport_stock_list.php" class="btn btn-info">Transport Stock List</a>
                <a href="sales_report_inventory.php" class="btn btn-warning">Sales Report</a>
              </div>
            </div>
          </div>
          <br>
          <table class="table table-bordered table-striped">
            <thead>
              <tr>
                <th>Product Name</th>
                <th>Product Code</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>Created Date</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $query = "SELECT * FROM main_stock ORDER BY created_date DESC";
              $result = $db->query($query);
              while($row = $result->fetch_assoc()):
              ?>
              <tr>
                <td><?php echo remove_junk($row['product_name']); ?></td>
                <td><?php echo remove_junk($row['product_code']); ?></td>
                <td><?php echo (int)$row['quantity']; ?></td>
                <td><?php echo CURRENCY; ?><?php echo number_format($row['price'], 2); ?></td>
                <td><?php echo read_date($row['created_date']); ?></td>
                <td>
                  <div class="btn-group">
                    <a href="edit_main_stock.php?id=<?php echo (int)$row['id']; ?>" class="btn btn-sm btn-info">Edit</a>
                    <a href="delete_main_stock.php?id=<?php echo (int)$row['id']; ?>" class="btn btn-sm btn-danger">Delete</a>
                    <a href="transfer_to_transport.php?product_id=<?php echo $row['id']; ?>" class="btn btn-sm btn-primary">Transfer</a>
                  </div>
                </td>
              </tr>
              <?php endwhile; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
<?php include_once('layouts/footer.php'); ?>