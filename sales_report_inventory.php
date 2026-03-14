<?php
  $page_title = 'Sales Report';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
   page_require_level(1);
   
  $start_date = isset($_GET['start_date']) ? $_GET['start_date'] : '';
  $end_date = isset($_GET['end_date']) ? $_GET['end_date'] : '';
  
  $query = "SELECT * FROM sales_inventory WHERE 1=1";
  if($start_date) $query .= " AND sale_date >= '{$start_date}'";
  if($end_date) $query .= " AND sale_date <= '{$end_date}'";
  $query .= " ORDER BY sale_date DESC";
  
  $result = $db->query($query);
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
            <span class="glyphicon glyphicon-stats"></span>
            <span>Sales Report</span>
         </strong>
        </div>
        <div class="panel-body">
          <form method="get" action="sales_report_inventory.php" class="form-inline">
            <div class="form-group">
              <label for="start_date">Start Date:</label>
              <input type="date" class="form-control" id="start_date" name="start_date" value="<?php echo $start_date; ?>">
            </div>
            <div class="form-group">
              <label for="end_date">End Date:</label>
              <input type="date" class="form-control" id="end_date" name="end_date" value="<?php echo $end_date; ?>">
            </div>
            <button type="submit" class="btn btn-primary">Filter</button>
            <a href="sales_report_inventory.php" class="btn btn-default">Clear</a>
            <?php if($start_date && $end_date): ?>
              <form method="post" action="sale_report_inventory_process.php" style="display:inline;">
                <input type="hidden" name="start-date" value="<?php echo $start_date; ?>">
                <input type="hidden" name="end-date" value="<?php echo $end_date; ?>">
                <button type="submit" name="submit" class="btn btn-success">Print Report</button>
              </form>
            <?php endif; ?>
          </form>
          <br>
          <table class="table table-bordered table-striped">
            <thead>
              <tr>
                <th>Shop Name</th>
                <th>Product</th>
                <th>Qty</th>
                <th>Price</th>
                <th>Total</th>
                <th>Date</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $total_sales = 0;
              while($row = $result->fetch_assoc()):
                $total_sales += $row['total'];
              ?>
              <tr>
                <td><?php echo remove_junk($row['shop_name']); ?></td>
                <td><?php echo remove_junk($row['product_name']); ?></td>
                <td><?php echo (int)$row['qty']; ?></td>
                <td><?php echo number_format($row['price'], 2); ?></td>
                <td><?php echo number_format($row['total'], 2); ?></td>
                <td><?php echo read_date($row['sale_date']); ?></td>
              </tr>
              <?php endwhile; ?>
            </tbody>
            <tfoot>
              <tr>
                <th colspan="4" style="text-align:right;">Total Sales:</th>
                <th><?php echo number_format($total_sales, 2); ?></th>
                <th></th>
              </tr>
            </tfoot>
          </table>
        </div>
      </div>
    </div>
  </div>
<?php include_once('layouts/footer.php'); ?>