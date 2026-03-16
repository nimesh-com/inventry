<?php
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
  page_require_level(1);
?>
<?php
  $main_stock = find_by_id('main_stock',(int)$_GET['id']);
  if(!$main_stock){
    $session->msg("d","Missing Main Stock id.");
    redirect('main_stock_list.php');
  }
?>
<?php
  $delete_id = delete_by_id('main_stock',(int)$main_stock['id']);
  if($delete_id){
      $session->msg("s","Main Stock deleted.");
      redirect('main_stock_list.php');
  } else {
      $session->msg("d","Main Stock deletion failed.");
      redirect('main_stock_list.php');
  }
?>