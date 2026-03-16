<?php
$page_title = 'Sales Report';
$results = '';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
   page_require_level(1);
?>
<?php
  if(isset($_POST['submit'])){
    $req_dates = array('start-date','end-date');
    validate_fields($req_dates);

    if(empty($errors)):
      $start_date   = remove_junk($db->escape($_POST['start-date']));
      $end_date     = remove_junk($db->escape($_POST['end-date']));
      $sql = "SELECT * FROM sales_inventory WHERE sale_date BETWEEN '{$start_date}' AND '{$end_date}' ORDER BY sale_date DESC";
      $results = find_by_sql($sql);
    else:
      $session->msg("d", $errors);
      redirect('sales_report_inventory.php', false);
    endif;

  } else {
    $session->msg("d", "Select dates");
    redirect('sales_report_inventory.php', false);
  }
?>
<!doctype html>
<html lang="en-US">
 <head>
   <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
   <title>Sales Report</title>
     <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css"/>
   <style>
   @media print {
     html,body{
        font-size: 9.5pt;
        margin: 0;
        padding: 0;
     }.page-break {
       page-break-before:always;
       width: auto;
       margin: auto;
      }
    }
    .page-break{
      width: 980px;
      margin: 0 auto;
    }
     .sale-head{
       margin: 40px 0;
       text-align: center;
     }.sale-head h1,.sale-head strong{
       padding: 10px 20px;
       display: block;
     }.sale-head h1{
       margin: 0;
       border-bottom: 1px solid #212121;
     }.table>thead:first-child>tr:first-child>th{
       border-top: 1px solid #000;
      }
      table thead tr th {
       text-align: center;
       border: 1px solid #ededed;
     }table tbody tr td{
       vertical-align: middle;
     }.sale-head,table.table thead tr th,table tbody tr td,table tfoot tr td{
       border: 1px solid #212121;
       white-space: nowrap;
     }.sale-head h1,table thead tr th,table tfoot tr td{
       background-color: #f8f8f8;
     }tfoot{
       color:#000;
       text-transform: uppercase;
       font-weight: 500;
     }
   </style>
</head>
<body>
  <?php if($results): ?>
    <div class="page-break">
       <div class="sale-head">
           <h1>Inventory Management System - Sales Report</h1>
           <strong><?php if(isset($start_date)){ echo $start_date;}?> TILL DATE <?php if(isset($end_date)){echo $end_date;}?> </strong>
       </div>
      <table class="table table-border">
        <thead>
          <tr>
              <th>Date</th>
              <th>Shop Name</th>
              <th>Product</th>
              <th>Qty</th>
              <th>Price</th>
              <th>Total</th>
          </tr>
        </thead>
        <tbody>
          <?php 
          $grand_total = 0;
          foreach($results as $result): 
            $grand_total += $result['total'];
          ?>
           <tr>
              <td class=""><?php echo remove_junk($result['sale_date']);?></td>
              <td class=""><?php echo remove_junk($result['shop_name']);?></td>
              <td class="desc">
                <h6><?php echo remove_junk(ucfirst($result['product_name']));?></h6>
              </td>
              <td class="text-right"><?php echo (int)$result['qty'];?></td>
              <td class="text-right"><?php echo CURRENCY; ?><?php echo number_format($result['price'], 2);?></td>
              <td class="text-right"><?php echo CURRENCY; ?><?php echo number_format($result['total'], 2);?></td>
          </tr>
        <?php endforeach; ?>
        </tbody>
        <tfoot>
         <tr class="text-right">
           <td colspan="5">Grand Total</td>
           <td><?php echo CURRENCY; ?><?php echo number_format($grand_total, 2);?></td>
         </tr>
        </tfoot>
      </table>
      <div style="text-align: center; margin-top: 20px;">
        <button onclick="window.location.href='sales_report_inventory.php'" class="btn btn-primary">Back to Sales Report</button>
      </div>
    </div>
  <?php
    else:
        $session->msg("d", "Sorry no sales has been found. ");
        redirect('sales_report_inventory.php', false);
     endif;
  ?>
</body>
</html>
<?php if(isset($db)) { $db->db_disconnect(); } ?>