<?php
include '../components/connection.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
    header('location:login.php');
    exit;
}

 $get_id = $_GET['post_id'];

/* delete product */
if (isset($_POST['delete'])) {
    if (isset($_POST['product_id']) && !empty($_POST['product_id'])) {
        $p_id = $_POST['product_id'];
        $p_id = filter_var($p_id, FILTER_SANITIZE_STRING);

        
    } else {
        $error_msg[] = 'Product ID is missing!';
    }
}
?>