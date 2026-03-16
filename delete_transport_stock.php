<?php
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
  page_require_level(1);
?>
<?php
  $transport_stock = find_by_id('transport_stock',(int)$_GET['id']);
  if(!$transport_stock){
    $session->msg("d","Missing Transport Stock id.");
    redirect('transport_stock_list.php');
  }
?>
<?php
  $delete_id = delete_by_id('transport_stock',(int)$transport_stock['id']);
  if($delete_id){
      $session->msg("s","Transport Stock deleted.");
      redirect('transport_stock_list.php');
  } else {
      $session->msg("d","Transport Stock deletion failed.");
      redirect('transport_stock_list.php');
  }
?>